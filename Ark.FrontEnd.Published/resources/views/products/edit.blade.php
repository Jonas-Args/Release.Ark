@extends('layouts.app')

@section('content')

<div id="appViewPort_Products">
<div class="row">
	<form class="form form-horizontal mar-top" action="{{route('products.update', $product->id)}}" method="POST" enctype="multipart/form-data" id="choice_form">
		<input name="_method" type="hidden" value="POST">
		<input type="hidden" name="id" value="{{ $product->id }}">
		@csrf
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">{{__('Product Information')}}</h3>
			</div>
			<div class="panel-body">
				<div class="tab-base tab-stacked-left">
				    <!--Nav tabs-->
				    <ul class="nav nav-tabs">
						<li class="active">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-1" aria-expanded="true">{{__('General')}}</a>
				        </li>
				        <li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-2" aria-expanded="false">{{__('Images')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-3" aria-expanded="false">{{__('Videos')}}</a>
				        </li>
				        <li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-4" aria-expanded="false">{{__('Meta Tags')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-5" aria-expanded="false">{{__('Customer Choice')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-6" @click="DispalyPriceRangeData({{$product->id}})" aria-expanded="false">{{__('Price')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-7" aria-expanded="false">{{__('Description')}}</a>
				        </li>
						{{-- <li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-8" aria-expanded="false">Display Settings</a>
				        </li> --}}
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-9" aria-expanded="false">{{__('Shipping Info')}}</a>
				        </li>
						<li class="">
				            <a data-toggle="tab" href="#demo-stk-lft-tab-10" aria-expanded="false">{{__('PDF Specification')}}</a>
				        </li>
				    </ul>

				    <!--Tabs Content-->
				    <div class="tab-content">
				        <div id="demo-stk-lft-tab-1" class="tab-pane fade active in">
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Product Name')}}</label>
	                            <div class="col-lg-7">
	                                <input type="text" class="form-control" name="name" placeholder="{{__('Product Name')}}" value="{{$product->name}}" required>
	                            </div>
	                        </div>
	                        <div class="form-group" id="category">
	                            <label class="col-lg-2 control-label">{{__('Category')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="category_id" id="category_id" required>
	                                	<option>Select an option</option>
	                                	@foreach($categories as $category)
	                                	    <option value="{{$category->id}}" <?php if($product->category_id == $category->id) echo "selected"; ?> >{{__($category->name)}}</option>
	                                	@endforeach
	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group" id="subcategory">
	                            <label class="col-lg-2 control-label">{{__('Subcategory')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="subcategory_id" id="subcategory_id" required>

	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group" id="subsubcategory">
	                            <label class="col-lg-2 control-label">{{__('Sub Subcategory')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="subsubcategory_id" id="subsubcategory_id" required>

	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group" id="brand">
	                            <label class="col-lg-2 control-label">{{__('Brand')}}</label>
	                            <div class="col-lg-7">
	                                <select class="form-control demo-select2-placeholder" name="brand_id" id="brand_id" required>

	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Unit')}}</label>
	                            <div class="col-lg-7">
	                                <input type="text" class="form-control" name="unit" placeholder="Unit (e.g. KG, Pc etc)" value="{{$product->unit}}" required>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Tags')}}</label>
	                            <div class="col-lg-7">
	                                <input type="text" class="form-control" name="tags[]" id="tags" value="{{ $product->tags }}" placeholder="Type to add a tag" data-role="tagsinput">
	                            </div>
	                        </div>
				        </div>
				        <div id="demo-stk-lft-tab-2" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Main Images')}}</label>
								<div class="col-lg-7">
									<div id="photos">
										@if(is_array(json_decode($product->photos)))
											@foreach (json_decode($product->photos) as $key => $photo)
												<div class="col-md-4 col-sm-4 col-xs-6">
													<div class="img-upload-preview">
														<img loading="lazy"  src="{{ asset($photo) }}" alt="" class="img-responsive">
														<input type="hidden" name="previous_photos[]" value="{{ $photo }}">
														<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
													</div>
												</div>
											@endforeach
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Thumbnail Image')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="thumbnail_img">
										@if ($product->thumbnail_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->thumbnail_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_thumbnail_img" value="{{ $product->thumbnail_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Featured')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="featured_img">
										@if ($product->featured_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->featured_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_featured_img" value="{{ $product->featured_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Flash Deal')}} <small>(290x300)</small></label>
								<div class="col-lg-7">
									<div id="flash_deal_img">
										@if ($product->flash_deal_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->flash_deal_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_flash_deal_img" value="{{ $product->flash_deal_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
				        </div>
				        <div id="demo-stk-lft-tab-3" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Video Provider')}}</label>
								<div class="col-lg-7">
									<select class="form-control demo-select2-placeholder" name="video_provider" id="video_provider">
										<option value="youtube" <?php if($product->video_provider == 'youtube') echo "selected";?> >{{__('Youtube')}}</option>
										<option value="dailymotion" <?php if($product->video_provider == 'dailymotion') echo "selected";?> >{{__('Dailymotion')}}</option>
										<option value="vimeo" <?php if($product->video_provider == 'vimeo') echo "selected";?> >{{__('Vimeo')}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Video Link')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="video_link" value="{{ $product->video_link }}" placeholder="Video Link">
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-4" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Meta Title')}}</label>
								<div class="col-lg-7">
									<input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}" placeholder="{{__('Meta Title')}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('Description')}}</label>
								<div class="col-lg-7">
									<textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">{{ __('Meta Image') }}</label>
								<div class="col-lg-7">
									<div id="meta_photo">
										@if ($product->meta_img != null)
											<div class="col-md-4 col-sm-4 col-xs-6">
												<div class="img-upload-preview">
													<img loading="lazy"  src="{{ asset($product->meta_img) }}" alt="" class="img-responsive">
													<input type="hidden" name="previous_meta_img" value="{{ $product->meta_img }}">
													<button type="button" class="btn btn-danger close-btn remove-files"><i class="fa fa-times"></i></button>
												</div>
											</div>
										@endif
									</div>
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-5" class="tab-pane fade">
							<div class="form-group">
								<div class="col-lg-2">
									<input type="text" class="form-control" value="{{__('Colors')}}" disabled>
								</div>
								<div class="col-lg-7">
									<select class="form-control color-var-select" name="colors[]" id="colors" multiple>
										@foreach (\App\Color::orderBy('name', 'asc')->get() as $key => $color)
											<option value="{{ $color->code }}" <?php if(in_array($color->code, json_decode($product->colors))) echo 'selected'?> >{{ $color->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-2">
									<label class="switch" style="margin-top:5px;">
										<input value="1" type="checkbox" name="colors_active" <?php if(count(json_decode($product->colors)) > 0) echo "checked";?> >
										<span class="slider round"></span>
									</label>
								</div>
							</div>

							<div class="customer_choice_options" id="customer_choice_options">
								@foreach (json_decode($product->choice_options) as $key => $choice_option)
									<div class="form-group">
										<div class="col-lg-2">
											<input type="hidden" name="choice_no[]" value="{{ explode('_', $choice_option->name)[1] }}">
											<input type="text" class="form-control" name="choice[]" value="{{ $choice_option->title }}" placeholder="Choice Title">
										</div>
										<div class="col-lg-7">
											<input type="text" class="form-control" name="choice_options_{{ explode('_', $choice_option->name)[1] }}[]" placeholder="Enter choice values" value="{{ implode(',', $choice_option->options) }}" data-role="tagsinput" onchange="update_sku()">
										</div>
										<div class="col-lg-2">
											<button onclick="delete_row(this)" class="btn btn-danger btn-icon"><i class="demo-psi-recycling icon-lg"></i></button>
										</div>
									</div>
								@endforeach
							</div>
							<div class="form-group">
								<div class="col-lg-2">
									<button type="button" class="btn btn-info" onclick="add_more_customer_choice_option()">{{ __('Add more customer choice option') }}</button>
								</div>
							</div>
				        </div>
						<div id="demo-stk-lft-tab-6" class="tab-pane fade">

							<div class="customer_choice_options" id="price_choice_options">

							</div>
							

							<div class="form-group" style="display:none">
	                            <label class="col-lg-2 control-label">{{__('Unit price')}}</label>
	                            <div class="col-lg-7">
	                                <input type="text" placeholder="{{__('Unit price')}}" name="unit_price" class="form-control" value="{{$product->unit_price}}" required>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Purchase price')}}</label>
	                            <div class="col-lg-7">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('Purchase price')}}" name="purchase_price" class="form-control" value="{{$product->purchase_price}}" required>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Tax')}}</label>
	                            <div class="col-lg-7">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('tax')}}" name="tax" class="form-control" value="{{$product->tax}}" required>
	                            </div>
	                            <div class="col-lg-1">
	                                <select class="demo-select2" name="tax_type" required>
	                                	<option value="amount" <?php if($product->tax_type == 'amount') echo "selected";?> >$</option>
	                                	<option value="percent" <?php if($product->tax_type == 'percent') echo "selected";?> >%</option>
	                                </select>
	                            </div>
	                        </div>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Discount')}}</label>
	                            <div class="col-lg-7">
	                                <input type="number" min="0" step="0.01" placeholder="{{__('Discount')}}" name="discount" class="form-control" value="{{ $product->discount }}" required>
	                            </div>
	                            <div class="col-lg-1">
	                                <select class="demo-select2" name="discount_type" required>
	                                	<option value="amount" <?php if($product->discount_type == 'amount') echo "selected";?> >$</option>
	                                	<option value="percent" <?php if($product->discount_type == 'percent') echo "selected";?> >%</option>
	                                </select>
	                            </div>
							</div>
							<div class="form-group" id="quantity">
							    <label class="col-lg-2 control-label">{{__('Quantity')}}</label>
							    <div class="col-lg-7">
							        <input type="number" min="0" value="{{ $product->current_stock }}" step="1"
							            placeholder="{{__('Quantity')}}" name="current_stock" class="form-control" required>
							    </div>
							</div>
						<hr>
							<div class="row">
							    <div class="col-md-12">
							        <div class="panel panel-primary">
							            <div class="panel-heading">
							                <h3 class="panel-title">Price Range</h3>
							                <div class="pull-right">
							                    <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter"
							                        data-container="body">
							                        <i class="glyphicon glyphicon-filter"></i>
							                    </span>
							                </div>
							            </div>
							            <div class="panel-body">
							                <input type="text" class="form-control" id="dev-table-filter" data-action="filter"
							                    data-filters="#dev-table" placeholder="Filter Developers" />

							                <table class="table table-hover" id="dev-table">
							                    <thead>
							                        <tr>
							                            <th>#</th>
							                            <th>From Qty</th>
							                            <th>To Qty</th>
							                            <th>Unit Price</th>
							                            <th></th>
							                        </tr>
							                    </thead>
							                    <tbody>
													@verbatim
													<tr v-for="(rowItem, x) in priceRange" v-bind:key="x">
							                          <td>{{x + 1}}</td>
							                          <td>{{rowItem.range_from}}</td>
							                          <td>{{rowItem.range_to}}</td>
							                          <td>{{rowItem.unit_price}}</td>
							                          <td width="100px">
							                              <div class="btn-group dropdown">
							                                  <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
							                                      data-toggle="dropdown" type="button">
							                                      Actions <i class="dropdown-caret"></i>
							                                  </button>
							                                  <ul class="dropdown-menu dropdown-menu-right">
							                                      <li><a @click="ShowModal('EditProductPrice', JSON.stringify(rowItem))">Edit</a></li>
							                                      <li><a @click="RemovePriceRangeData(rowItem)">Delete</a>
							                                      </li>
							                                  </ul>
							                              </div>
							                          </td>
													</tr>
													@endverbatim
							                    </tbody>
							                </table>

							                <div class="form-group" style="margin-bottom:0px">
							                    <div class="col-lg-2">
							                        <button type="button" class="btn btn-info btn-primary"
							                            @click="ShowModal('AddProductPrice')">{{ __('Add more price choice option') }}</button>
							                    </div>
							                </div>
							            </div>

							        </div>

							    </div>
							</div>

						

							<br>
							<div class="panel panel-primary">
							            <div class="panel-heading">
							                <h3 class="panel-title">Variants</h3>
							                <div class="pull-right">
							                    <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter"
							                        data-container="body">
							                        <i class="glyphicon glyphicon-filter"></i>
							                    </span>
							                </div>
							            </div>
							            <div class="panel-body">
										   
											<div class="sku_combination" id="sku_combination">

							                <div class="form-group" style="margin-bottom:0px">
							                    <div class="col-lg-2">
							                        <button type="button" class="btn btn-info btn-primary"
							                            onclick="add_more_price_choice_option()">{{ __('Add more price choice option') }}</button>
							                    </div>
							                </div>
							            </div>

							        </div>
							

							</div>
				        </div>
						<div id="demo-stk-lft-tab-7" class="tab-pane fade">
							<div class="form-group">
	                            <label class="col-lg-2 control-label">{{__('Description')}}</label>
	                            <div class="col-lg-9">
	                                <textarea class="editor" name="description">{{$product->description}}</textarea>
	                            </div>
	                        </div>
				        </div>
						{{-- <div id="demo-stk-lft-tab-8" class="tab-pane fade">

				        </div> --}}
						<div id="demo-stk-lft-tab-9" class="tab-pane fade">
							<div class="row bord-btm">
								<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Free Shipping')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Status')}}</label>
										<div class="col-lg-7">
											<label class="switch" style="margin-top:5px;">
												<input type="radio" name="shipping_type" value="free" @if($product->shipping_type == 'free') checked @endif>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="row bord-btm">
								<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Local Pickup')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Status')}}</label>
										<div class="col-lg-7">
											<label class="switch" style="margin-top:5px;">
												<input type="radio" name="shipping_type" value="local_pickup" @if($product->shipping_type == 'local_pickup') checked @endif>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Shipping cost')}}</label>
										<div class="col-lg-7">
											<input type="number" min="0" step="0.01" placeholder="{{__('Shipping cost')}}" name="local_pickup_shipping_cost" class="form-control" value="{{ $product->shipping_cost }}" required>
										</div>
									</div>
								</div>
							</div>

							<div class="row bord-btm">
								<div class="col-md-2">
									<div class="panel-heading">
										<h3 class="panel-title">{{__('Flat Rate')}}</h3>
									</div>
								</div>
								<div class="col-md-10">
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Status')}}</label>
										<div class="col-lg-7">
											<label class="switch" style="margin-top:5px;">
												<input type="radio" name="shipping_type" value="flat_rate" @if($product->shipping_type == 'flat_rate') checked @endif>
												<span class="slider round"></span>
											</label>
										</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">{{__('Shipping cost')}}</label>
										<div class="col-lg-7">
											<input type="number" min="0" step="0.01" placeholder="{{__('Shipping cost')}}" name="flat_shipping_cost" class="form-control" value="{{ $product->shipping_cost }}" required>
										</div>
									</div>
								</div>
								
							</div>

							<div class="row bord-btm">
							    <div class="col-md-2">
							        <div class="panel-heading">
							            <h3 class="panel-title">{{__('Other Options')}}</h3>
							        </div>
							    </div>
							    <div class="col-md-10" style="margin-top:10px">
							        <div class="form-group">
							            <label class="col-lg-2 control-label">{{__('Shipping Points')}}</label>
							            <div class="col-lg-7">
							                <input type="number" min="0" step="0.01" placeholder="{{__('Shipping Points')}}"
							                    name="shippingPoints" class="form-control" value="{{ number_format($product->shippingPoints[0]->point_value, 2) }}"
							                    required>
							            </div>
							        </div>
							    </div>

							</div>

							</div>
						<div id="demo-stk-lft-tab-10" class="tab-pane fade">
							<div class="form-group">
								<label class="col-lg-2 control-label">{{__('PDF Specification')}}</label>
								<div class="col-lg-7">
									<input type="file" class="form-control" placeholder="{{__('PDF')}}" name="pdf" accept="application/pdf">
								</div>
							</div>
				        </div>
				    </div>
				</div>
			</div>
			<div class="panel-footer text-right">
				<button type="submit" name="button" class="btn btn-purple">{{ __('Save') }}</button>
			</div>
		</div>
	</form>
</div>


<!-- Modal -->
<div class="modal fade" id="productPriceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	  <h4 class="modal-title" id="myModalLabel">@{{modalHeader}}</h4>
      </div>
      <div class="modal-body">
		
		<div v-if="modalIntent == 'EditProductPrice' || modalIntent == 'AddProductPrice'">
			<input type="hidden" class="form-control" v-model="modalData.product_id">
			<div class="input-group">
		        <span class="input-group-addon">From Qty</span>
		        <input type="text" class="form-control" v-model="modalData.range_from" placeholder="Qty">
		    </div>
			<br>
			<div class="input-group">
		        <span class="input-group-addon">To Qty</span>
		        <input type="text" class="form-control" v-model="modalData.range_to" placeholder="Qty">
			</div>
			<br>
			<div class="input-group">
		        <span class="input-group-addon">Unit Price</span>
		        <input type="text" class="form-control" v-model="modalData.unit_price" placeholder="Price Php">
		    </div>
		</div>
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" @click="UpdatePriceRangeData()" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div>


@endsection

@section('script')

<script type="text/javascript">

		var appView_products = new Vue({
		    el: '#appViewPort_Products',
		    data: {
				message: 'Hello Vue!',
				modalIntent: '',
				modalHeader: '',
				productID: '',
				modalData: [],
		        priceRange: []
		    },
		    methods: {
		        DispalyPriceRangeData(x, url = '{{ route('products.admin.price_range') }}') {
					this.productID = x;
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x
		                })
		                .then(data => {
							document.body.style.cursor = "default";
		                    this.priceRange = data.data;
		                });
				},
		        UpdatePriceRangeData(x = this.modalData, url = '{{ route('products.admin.price_range.update') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x.id,
		                    range_from: x.range_from,
		                    range_to: x.range_to,
							unit_price: x.unit_price,
							product_id: this.productID
		                })
		                .then(data => {
							document.body.style.cursor = "default";
							this.DispalyPriceRangeData(this.productID);
							$('#productPriceModal').modal('hide');
		                });
				},
				 RemovePriceRangeData(x, url = '{{ route('products.admin.price_range.remove') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x.id
		                })
		                .then(data => {
							document.body.style.cursor = "default";
							this.DispalyPriceRangeData(this.productID);
		                });
				},
				ShowModal(x,y) {
					switch (x) {
						case 'EditProductPrice':
							this.modalHeader = "Edit Price Range";
							this.modalIntent = "EditProductPrice";
							rwData = JSON.parse(y);
							this.modalData = rwData;
							break;
						case 'AddProductPrice':
							this.modalHeader = "New Price Range";
							this.modalIntent = "AddProductPrice";
							this.modalData = [];
							break;
					
						default:
							break;
					}

				    $('#productPriceModal').modal('show');
				}
		    },
		    created() {
		    }
		});

	function EditProductPrice(params) {
		$('#productPriceModal').modal('show');
	}

	var i = $('input[name="choice_no[]"').last().val();
	if(isNaN(i)){
		i =0;
	}

	function add_more_price_choice_option(){
		$('#price_choice_options').append('<div class="form-group"><div class="col-lg-2"><input type="hidden" name="choice_no[]" value="'+i+'"> <div><label class="control-label" style="width:100%">Unit Price</label></div> <div><label class="control-label" style="width:100%">Quantity</label></div> </div><div class="col-lg-7"><input type="text" class="form-control" name="choice[]" value="" placeholder=""> <input type="number" class="form-control" name="choice[]" value="" placeholder=""></div><div class="col-lg-2"><button onclick="delete_row(this)" class="btn btn-danger btn-icon"><i class="demo-psi-recycling icon-lg"></i></button></div></div>');
		i++;
		$("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
	}

	function add_more_customer_choice_option(){
		i++;
		$('#customer_choice_options').append('<div class="form-group"><div class="col-lg-2"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="" placeholder="Choice Title"></div><div class="col-lg-7"><input type="text" class="form-control" name="choice_options_'+i+'[]" placeholder="Enter choice values" data-role="tagsinput" onchange="update_sku()"></div><div class="col-lg-2"><button onclick="delete_row(this)" class="btn btn-danger btn-icon"><i class="demo-psi-recycling icon-lg"></i></button></div></div>');
		$("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
	}

	$('input[name="colors_active"]').on('change', function() {
	    if(!$('input[name="colors_active"]').is(':checked')){
			$('#colors').prop('disabled', true);
		}
		else{
			$('#colors').prop('disabled', false);
		}
		update_sku();
	});

	$('#colors').on('change', function() {
	    update_sku();
	});

	$('input[name="unit_price"]').on('keyup', function() {
	    update_sku();
	});

	function delete_row(em){
		$(em).closest('.form-group').remove();
		update_sku();
	}

	function update_sku(){
		$.ajax({
		   type:"POST",
		   url:'{{ route('products.sku_combination_edit') }}',
		   data:$('#choice_form').serialize(),
		   success: function(data){
			   $('#sku_combination').html(data);
			   if (!data) {
				   $('#quantity').show();
			   }
			   else {
			   		$('#quantity').hide();
			   }
		   }
	   });
	}

	function get_subcategories_by_category(){
		var category_id = $('#category_id').val();
		$.post('{{ route('subcategories.get_subcategories_by_category') }}',{_token:'{{ csrf_token() }}', category_id:category_id}, function(data){
		    $('#subcategory_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#subcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
		    $("#subcategory_id > option").each(function() {
		        if(this.value == '{{$product->subcategory_id}}'){
		            $("#subcategory_id").val(this.value).change();
		        }
		    });

		    $('.demo-select2').select2();

		    get_subsubcategories_by_subcategory();
		});
	}

	function get_subsubcategories_by_subcategory(){
		var subcategory_id = $('#subcategory_id').val();
		$.post('{{ route('subsubcategories.get_subsubcategories_by_subcategory') }}',{_token:'{{ csrf_token() }}', subcategory_id:subcategory_id}, function(data){
		    $('#subsubcategory_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#subsubcategory_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
		    $("#subsubcategory_id > option").each(function() {
		        if(this.value == '{{$product->subsubcategory_id}}'){
		            $("#subsubcategory_id").val(this.value).change();
		        }
		    });

		    $('.demo-select2').select2();

		    get_brands_by_subsubcategory();
		});
	}

	function get_brands_by_subsubcategory(){
		var subsubcategory_id = $('#subsubcategory_id').val();
		$.post('{{ route('subsubcategories.get_brands_by_subsubcategory') }}',{_token:'{{ csrf_token() }}', subsubcategory_id:subsubcategory_id}, function(data){
		    $('#brand_id').html(null);
		    for (var i = 0; i < data.length; i++) {
		        $('#brand_id').append($('<option>', {
		            value: data[i].id,
		            text: data[i].name
		        }));
		    }
		    $("#brand_id > option").each(function() {
		        if(this.value == '{{$product->brand_id}}'){
		            $("#brand_id").val(this.value).change();
		        }
		    });

		    $('.demo-select2').select2();

		});
	}

	$(document).ready(function(){
		$('#container').removeClass('mainnav-lg').addClass('mainnav-sm');
	    get_subcategories_by_category();
		$("#photos").spartanMultiImagePicker({
			fieldName:        'photos[]',
			maxCount:         10,
			rowHeight:        '200px',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});
		$("#thumbnail_img").spartanMultiImagePicker({
			fieldName:        'thumbnail_img',
			maxCount:         1,
			rowHeight:        '200px',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});
		$("#featured_img").spartanMultiImagePicker({
			fieldName:        'featured_img',
			maxCount:         1,
			rowHeight:        '200px',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});
		$("#flash_deal_img").spartanMultiImagePicker({
			fieldName:        'flash_deal_img',
			maxCount:         1,
			rowHeight:        '200px',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});
		$("#meta_photo").spartanMultiImagePicker({
			fieldName:        'meta_img',
			maxCount:         1,
			rowHeight:        '200px',
			groupClassName:   'col-md-4 col-sm-4 col-xs-6',
			maxFileSize:      '',
			dropFileLabel : "Drop Here",
			onExtensionErr : function(index, file){
				console.log(index, file,  'extension err');
				alert('Please only input png or jpg type file')
			},
			onSizeErr : function(index, file){
				console.log(index, file,  'file size too big');
				alert('File size too big');
			}
		});

		update_sku();

		$('.remove-files').on('click', function(){
            $(this).parents(".col-md-4").remove();
        });
	});

	$('#category_id').on('change', function() {
	    get_subcategories_by_category();
	});

	$('#subcategory_id').on('change', function() {
	    get_subsubcategories_by_subcategory();
	});

	$('#subsubcategory_id').on('change', function() {
	    get_brands_by_subsubcategory();
	});

</script>

@endsection
