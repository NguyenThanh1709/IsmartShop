<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Config;
use App\Models\Product;
use App\Models\ProductCat;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;

use function App\Http\Controllers\data_tree as ControllersData_tree;

class AdminProductController extends Controller
{
    //construct
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });

        function data_tree($data_src, $parent_id = 0, $level = 0)
        {
            $result = array();
            foreach ($data_src as $item) {
                if ($item['parent_id'] == $parent_id) {
                    $item['level'] = $level;
                    $result[] = $item;
                    $child = data_tree($data_src, $item['id'], $level + 1);
                    $result = array_merge($result, $child);
                }
            }
            return $result;
        }
    }

    //////////////////
    //---Product---///
    //////////////////

    public function listProduct(Request $request)
    {
        $status = $request->input('status');
        $list_option = [
            'public' => 'Công khai',
            'pending' => 'Chờ duyệt',
            'disable' => 'Thùng rác'
        ];
        if ($status == 'trash') {
            $list_option = [
                'restore' => 'Khôi phục',
                'delete' => 'Xoá vĩnh viễn'
            ];
            $listProduct = Product::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'public') {
            $list_option = [
                'pending' => 'Chờ duyệt',
                'disable' => 'Thùng rác'
            ];
            $listProduct = Product::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else if ($status == 'pending') {
            $list_option = [
                'public' => 'Công khai',
                'disable' => 'Thùng rác'
            ];
            $listProduct = Product::where('status', $status)->orderBy('id', 'desc')->paginate(20);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $listProduct = Product::where('name', 'LIKE', "%{$keyword}%")->orderBy('id', 'desc')->paginate(20);
        }
        $countProductPublic = Product::where('status', 'public')->count();
        $countProductPending = Product::where('status', 'pending')->count();
        $countProductTrash = Product::onlyTrashed()->count();
        $count = [$countProductPublic, $countProductPending, $countProductTrash];

        return view('admin.product.listProduct', compact('listProduct', 'count', 'status', 'list_option'));
    }

    public function addProduct()
    {
        if (Gate::allows('product.add')) {
            $dataProduct = ProductCat::all();
            $config = Config::orderBy('storage_capacity','ASC')->get();
            $listBrands = Brand::all();
            $listProductCats = data_tree($dataProduct, 0, 0);
            $listColors = Color::all();
            return view('admin.product.addProduct', compact('listProductCats', 'listBrands', 'listColors', 'config'));
        } else {
            return Redirect::route('index.product')->with('status-danger', 'Bạn không được quyền truy cập chức năng này');
        }
    }

    public function storeProduct(Request $request)
    {
        if (Gate::allows('product.add')) {
            //Validate form 
            $request->validate(
                [
                    'name' => 'required', 
                    'slug' => 'required',
                    'status' => 'required|in:public,pending',
                    'cat' => 'required',
                    'brand' => 'required',
                    'desc' => 'required',
                    'content' => 'required',
                    // 'price-old' => 'nullable|numeric|min:1',
                    // 'price-new' => 'nullable|numeric|min:1',
                    // 'price.*.*' => 'nullable|numeric|min:1', // Xác nhận 'price' là số không âm hoặc có thể là null
                    // 'sale_off.*.*' => 'nullable|numeric|min:1', // Xác nhận 'sale_off' là số không âm hoặc có thể là null
                    'thumbnail' => ['image', 'mimes:jpeg,png,webp,jpg,gif,svg'],
                ],
                [
                    'numeric' => 'Dữ liệu nhập vào không đúng định dạng là số',
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái',
                    'image' => 'File tải lên phải là hình ảnh có đuôi (.jpeg, .png, .webp, .jpg, .gif, .svg)',
                ],
                [
                    'price-old' => 'Giá cũ',
                    'price-new' => 'Giá mới',
                    'name' => 'Tên sản phẩm',
                    'status' => 'Trạng thái',
                    'cat' => 'Danh mục',
                    'brand' => 'Thương hiệu',
                    'desc' => 'Mô tả ngắn',
                    'content' => 'Thông tin chi tiết sản phẩm',
                    'thumbnail' => 'Ảnh nổi bật'
                ]
            );

            
            //End Validate form

            //Upload thumbnail products
            if ($request->hasFile('thumbnail')) {
                $file = $request->thumbnail;
                $name = $file->getClientOriginalName();
                $file->move('public/uploads', $file->getClientOriginalName());
                $thumbnail = 'public/uploads/' . $name;
            }
            //Upload multiple images products
            if ($request->hasfile('listFile')) {
                foreach ($request->file('listFile') as $file) {
                    $name = $file->getClientOriginalName();
                    $urlImage = strtolower('public/uploads/' . $name);
                    $file->move('public/uploads', $file->getClientOriginalName());
                    $listImages[] = $urlImage;
                }
            }
            //Create record in database
            $product = Product::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'status' => $request->input('status'),
                'content' => $request->input('content'),
                'desc' => $request->input('desc'),
                'brand_id' => $request->input('brand'),
                'product_cat_id' => $request->input('cat'),
                'user_id' => Auth::user()->id,
                'thumbnail' => $thumbnail,
                'images' => $listImages,
            ]);
            $product_id = $product->id;

            if ($request->input('color') && $request->input('config')) {
                $colorInput = $request->input('color');
                $configInput = $request->input('config');

                $represent = 0;
                foreach ($colorInput as $key => $color) {
                    foreach ($configInput as $config => $value) {
                        if (!$config) {
                            break;
                        }
                        $represent++;
                        
                        $price = str_replace(',', '', $request->price[$config][$color]);
        
                        $saleOff = str_replace(',', '', $request->sale_off[$config][$color]);

                        $quantity = $request->colorQuantity[$config][$color] ?? 0;

                        $discount = ($price != 0) ? round(($price - $saleOff) / $price * 100) : 0;

                        if ($price != 0) {
                            $product->productWareHourse()->create([
                                'product_id' => $product_id,
                                'color_id' => $color,
                                'config_id' => $config,
                                'price' => $price,
                                'sale_off' => $saleOff,
                                'discount' => $discount,
                                'quantity' => $quantity,
                                'represent' => $represent
                            ]);
                        }
                    }
                }
            } else if ($request->input('price-old')) {

                $priceOld = $request->input('price-old');
                $priceOld = str_replace(',', '',$priceOld);
                $priceNew = $request->input('price-new');
                $priceNew = str_replace(',', '',$priceNew);
                $quantity = $request->input('quantity');

                $discount = (!empty($priceNew)) ? round(($priceOld - $priceNew) / $priceOld * 100) : 0;

                $product->productWareHourse()->create([
                    'product_id' => $product_id,
                    'price' => $priceOld,
                    'sale_off' => $priceNew,
                    'discount' => $discount,
                    'quantity' => $quantity,
                    'represent' => 1
                ]);
            }


            return Redirect::route('index.product')->with('status', 'Đã thêm sản phẩm mới thành công');
        } else {
            return Redirect::route('index.product')->with('status-danger', 'Bạn không được quyền truy cập chức năng này');
        }
    }

    public function actionProduct(Request $request)
    {
        if (Gate::allows('product.edit')) {
            $list_check = $request->input('list_check');
            if ($list_check) {
                if (!empty($list_check)) {
                    $act = $request->input('act');
                    switch ($act) {
                        case "disable":
                            Product::destroy($list_check);
                            Warehouse::whereIn('product_id', $list_check)->delete();
                            return redirect('admin/product/list?status=trash')->with('status', 'Đã chuyển dữ liệu sản phẩm vào thùng rác');
                            break;
                        case "restore":
                            Product::withTrashed()->whereIn('id', $list_check)->restore();
                            Warehouse::withTrashed()->whereIn('product_id', $list_check)->restore();
                            return redirect()->route('index.product')->with('status', 'Đã khôi phục thành công dữ liệu sản phẩm');
                            break;
                        case "delete":
                            Product::withTrashed()->whereIn('id', $list_check)->forceDelete();
                            return redirect('admin/product/list?status=trash')->with('status', 'Bạn đã xoá dữ liệu sản phẩm vĩnh viễn');
                            break;
                        case "public":
                            Product::whereIn('id', $list_check)->update(['status' => 'public']);
                            return redirect('admin/product/list?status=public')->with('status', 'Đã cập nhật thông tin dữ liệu sản phẩm thành công');
                            break;
                        case "pending":
                            Product::whereIn('id', $list_check)->update(['status' => 'pending']);
                            return redirect('admin/product/list?status=pending')->with('status', 'Đã cập nhật thông tin dữ liệu sản phẩm thành công');
                            break;
                    }
                }
            } else {
                return redirect()->route('index.product')->with('status-danger', 'Bạn cần click chọn đối tượng để thao tác');
            }
        } else {
            return redirect()->route('index.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteProduct($id)
    {
        try {
            if (Gate::allows('product.delete')) {
                $product = Product::find($id);
                $product->delete();
                Warehouse::where('product_id', $id)->delete();
                return redirect()->route('index.product')->with('status', 'Đã xoá sản phẩm thành công');
            } else {
                return redirect()->route('index.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
            }
        } catch (\Exception $e) {
            return redirect()->route('index.product')->with('status-danger', 'Đã xảy ra lỗi: ' . $e->getMessage());
        }
    }

    public function editProduct($id)
    {
        if (Gate::allows('product.edit')) {
            $dataProduct = ProductCat::all();
            $listBrands = Brand::all();
            $listProductCats = data_tree($dataProduct, 0, 0);
            $listColors = Color::all();
            $product = Product::find($id);
            $config = Config::orderBy('storage_capacity','ASC')->get();
            $warehouses = Warehouse::where('product_id', $id)->get();
            $images = $product->images;
            $listImages = explode(',', str_replace(['[', ']', '"'], '', $images));
            return view('admin.product.editProduct', compact(
                'listBrands',
                'listProductCats',
                'listColors',
                'product',
                'listImages',
                'config',
                'warehouses'
            ));
        } else {
            return redirect()->route('index.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

   public function updateProduct(Request $request, $id)
    {
        ///////////////////////
        //Update product info//
        ///////////////////////

        //Upload thumbnail products
        if (Gate::allows('product.edit')) {
            if ($request->hasFile('thumbnail')) {
                $file = $request->thumbnail;
                $name = $file->getClientOriginalName();
                $file->move('public/uploads/', $file->getClientOriginalName());
                $thumbnail = 'public/uploads/' . $name;
            } else {
                $product = Product::find($id);
                $thumbnail = $product->thumbnail;
            }
            //Upload multiple images products
            if ($request->hasfile('listFile')) {
                foreach ($request->file('listFile') as $file) {
                    $name = $file->getClientOriginalName();
                    $urlImage = strtolower('public/uploads/' . $name);
                    $file->move('public/uploads/', $file->getClientOriginalName());
                    $listImages[] = $urlImage;
                }
            } else {
                $product = Product::find($id);
                $listImages = str_replace('\\', '', $product->images);
            }

            $product = Product::find($id)->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'status' => $request->input('status'),
                'content' => $request->input('content'),
                'desc' => $request->input('desc'),
                'brand_id' => $request->input('brand'),
                'product_cat_id' => $request->input('cat'),
                'user_id' => Auth::user()->id,
                'thumbnail' => $thumbnail,
                'images' => $listImages,
            ]);
            ///////////////////////////
            //End Update product info//
            ///////////////////////////

            /////////////////////////////////////
            //Update info product in warehouses//
            /////////////////////////////////////
            $product_id = $id;
            Warehouse::where('product_id', $id)->forceDelete();
            if ($request->input('color') && $request->input('config')) {
                //Foreach array color
                $represent = 0;
                foreach ($request->input('color') as $key => $color) {
                    //Foreach array config
                    foreach ($request->input('config') as $config => $value) {
                        if (!$config) {
                            break;
                        }
                        $represent++;
                        $priceDefaul = str_replace(',','',$request->price[$config][$color]);
                        $priceSale = str_replace(',','',$request->sale_off[$config][$color]);

                        //Calculate discount
                        $discount = !empty($priceDefaul) ? round((($priceDefaul - $priceSale) / $priceDefaul) * 100) : 0;
                        $p = $request->price[$config][$color] ?? 0;
                        if ($p != 0) {
                            Warehouse::create([ //Many-to-many relationship Product and WareHouses
                                'product_id' => $product_id,
                                'color_id' => $color,
                                'config_id' => $config,
                                'price' => $priceDefaul,
                                'sale_off' => $priceSale,
                                'discount' => $discount,
                                'quantity' => $request->colorQuantity[$config][$color],
                                'represent' => $represent
                            ]);
                        } else {
                        }
                    }
                }
            }
            //Add product not smartphone
            else if ($request->input('price-old')) {
                $priceOld = str_replace(',','',$request->input('price-old'));
                $priceNew = str_replace(',','',$request->input('price-new'));

                $discount = !empty($priceNew) ? round((($priceOld - $priceNew) / $priceOld) * 100) : 0;
                Warehouse::create([ //Many-to-many relationship Product and WareHouses
                    'product_id' => $product_id,            
                    'price' => $priceOld,
                    'sale_off' => $priceNew,
                    'discount' => $discount,
                    'quantity' => $request->input('quantity'),
                    'represent' => 1
                ]);
            }
            return redirect()->route('index.product')->with('status', 'Cập nhật dữ liệu mới thành công');
        } else {
            return redirect()->route('index.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    //////////////////
    ///---Config---///
    //////////////////

    public function listConfig()
    {
        $listConfig = Config::orderby('storage_capacity', 'ASC')->get();
        return view('admin.product.listConfig', compact('listConfig'));
    }

    public function storeConfig(Request $request)
    {
        if (Gate::allows('product.config.add')) {
            $request->validate(
                [
                    'name' => 'required',
                    'storage_capacity' => 'required',
                    'status' => 'required|in:public,pending',
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái'
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái',
                    'storage_capacity' => 'Dung lượng'
                ]
            );
            Config::create([
                'name' => $request->input('name'),
                'user_id' => Auth::user()->id,
                'status' => $request->input('status'),
                'storage_capacity' => $request->input('storage_capacity')
            ]);
            return Redirect::back()->with('status', 'Thêm dữ liệu mới thành công');
        } else {
            return redirect()->route('index.config.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteConfig($id)
    {
        if (Gate::allows('product.config.delete')) {
            $Config = Config::find($id);
            $Config->delete();
            return redirect()->route('index.config.product')->with('status', 'Đã xoá danh mục bài viết thành công');
        } else {
            return redirect()->route('index.config.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editConfig($id)
    {
        $config = Config::find($id);
        $user = $config->user->name;
        return response()->json(['data' => $config, 'user' => $user], 200);
    }

    public function updateConfig(Request $request, $id)
    {
        if (Gate::allows('product.config.edit')) {
            Config::find($id)->update([
                'name' => $request->input('name'),
                'status' => $request->input('status'),
                'storage_capacity' => $request->input('storage_capacity'),
            ]);
            $request->session()->flash('status', 'Cập nhật dữ liệu mới thành công');
            return response()->json(['data' => 'success'], 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return response()->json(['data' => 'success'], 200);
        }
    }

    //////////////////
    //--ProductCat--//
    //////////////////
    public function listCat()
    {
        $listProductCat = ProductCat::all();
        $list_cat_tree = data_tree($listProductCat, 0, 0);
        $list_cat_tree_page = data_tree($listProductCat, 0, 0);
        return view('admin.product.listCat', compact('listProductCat', 'list_cat_tree', 'list_cat_tree_page'));
    }

    public function storeCat(Request $request)
    {
        if (Gate::allows('product.cat.add')) {
            $request->validate(
                [
                    'name' => 'required',
                    'slug' => 'required',
                    'status' => 'required|in:public,pending'
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái'
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái'
                ]
            );
            ProductCat::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'user_id' => Auth::user()->id,
                'parent_id' => $request->input('cat'),
                'status' => $request->input('status')
            ]);
            return Redirect::back()->with('status', 'Thêm dữ liệu mới thành công');
        } else {
            return redirect()->route('index.cat.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteCat($id)
    {
        if (Gate::allows('product.cat.add')) {
            $productCat = ProductCat::find($id);
            $productCat->delete();
            return redirect()->route('index.cat.product')->with('status', 'Đã xoá danh mục bài viết thành công');
        } else {
            return redirect()->route('index.cat.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editCat($id)
    {
        $cat = ProductCat::find($id);
        $user = $cat->user->name;
        return response()->json(['data' => $cat, 'user' => $user], 200);
    }

    public function updateCat(Request $request, $id)
    {
        if (Gate::allows('product.cat.edit')) {
            $cat = ProductCat::find($id);
            if ($cat->id != $request->input('cat')) {
                $cat = ProductCat::find($id)->update([
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'status' => $request->input('status'),
                    'parent_id' => $request->input('cat'),
                ]);
            } else {
                $cat = ProductCat::find($id)->update([
                    'name' => $request->input('name'),
                    'slug' => $request->input('slug'),
                    'status' => $request->input('status'),
                ]);
            }
            $request->session()->flash('status', 'Cập nhật dữ liệu mới thành công');
            return response()->json(['data' => 'success'], 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return response()->json(['data' => 'success'], 200);
        }
    }

    ////////////////////
    //--ProductColor--//
    ////////////////////

    public function listColor()
    {
        $listColors = Color::paginate(20);
        return view('admin.product.listColor', compact('listColors'));
    }

    public function deleteColor($id)
    {
        if (Gate::allows('product.color.delete')) {
            $color = Color::find($id);
            $color->delete();
            return redirect()->route('index.color.product')->with('status', 'Đã xoá bảng màu thành công');
        } else {
            return redirect()->route('index.color.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function storeColor(Request $request)
    {
        if (Gate::allows('product.color.add')) {
            $request->validate(
                [
                    'name' => 'required',
                    'slug' => 'required',
                    'color' => 'required',
                    'status' => 'required|in:public,pending',
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái'
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái',
                    'color' => 'Màu sắc'
                ]
            );
            Color::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'user_id' => Auth::user()->id,
                'code_color' => $request->input('color'),
                'status' => $request->input('status')
            ]);
            return Redirect::back()->with('status', 'Thêm dữ liệu mới thành công');
        } else {
            return redirect()->route('index.color.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editColor($id)
    {
        $color = Color::find($id);
        $user = $color->user->name;
        return response()->json(['data' => $color, 'user' => $user], 200);
    }

    public function updateColor(Request $request, $id)
    {
        if (Gate::allows('product.color.edit')) {
            Color::find($id)->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'status' => $request->input('status'),
                'code_color' => $request->input('color')
            ]);
            $request->session()->flash('status', 'Cập nhật dữ liệu mới thành công');
            return response()->json(['data' => 'success'], 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return response()->json(['data' => 'success'], 200);
        }
    }

    //////////////////////
    ///--ProductBrand--///
    //////////////////////

    public function listBrand()
    {
        $listBrands = Brand::paginate(20);
        return view('admin.product.listBrand', compact('listBrands'));
    }

    public function storeBrand(Request $request)
    {
        if (Gate::allows('product.brand.add')) {
            $request->validate(
                [
                    'name' => 'required',
                    'slug' => 'required',
                    'status' => 'required|in:public,pending',
                ],
                [
                    'required' => 'Không được để trống trường :attribute',
                    'in' => 'Vui lòng chọn trạng thái'
                ],
                [
                    'name' => 'Tên danh mục',
                    'status' => 'Trạng thái',
                ]
            );
            Brand::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'user_id' => Auth::user()->id,
                'status' => $request->input('status')
            ]);
            return Redirect::back()->with('status', 'Thêm dữ liệu mới thành công');
        } else {
            return redirect()->route('index.brand.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function deleteBrand($id)
    {
        if (Gate::allows('product.brand.delete')) {
            $brand = Brand::find($id);
            $brand->delete();
            return redirect()->route('index.brand.product')->with('status', 'Đã xoá thương hiệu thành công');
        } else {
            return redirect()->route('index.brand.product')->with('status-danger', 'Bạn không có quyền truy cập chức năng này');
        }
    }

    public function editBrand($id)
    {
        $brand = Brand::find($id);
        $user = $brand->user->name;
        return response()->json(['data' => $brand, 'user' => $user], 200);
    }

    public function updateBrand(Request $request, $id)
    {
        if (Gate::allows('product.brand.edit')) {
            Brand::find($id)->update([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'status' => $request->input('status'),
            ]);
            $request->session()->flash('status', 'Cập nhật dữ liệu mới thành công');
            return response()->json(['data' => 'success'], 200);
        } else {
            $request->session()->flash('status-danger', 'Bạn không có quyền truy cập chức năng này');
            return response()->json(['data' => 'success'], 200);
        }
    }
}
