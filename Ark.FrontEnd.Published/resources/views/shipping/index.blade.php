@extends('layouts.app')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<!-- <a href="{{ route('sellers.create')}}" class="btn btn-info pull-right">{{__('add_new')}}</a> -->
	</div>
</div>

<!-- Basic Data Tables -->
<!--===================================================-->
<div class="panel">
    <div class="panel-heading">
    </div>
    <div class="panel-body">
		<h1 style="margin:-25px 0px 20px 0px;">{{ __('Shipping Settings') }}</h1>

      
    </div>
</div>

<div id="appView_shippingFeeType">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Shipping Points Fibonacci</h3>
                    <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter"
                            data-container="body">
                            <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                <div class="panel-body">

                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>From Points</th>
                                <th>To Points</th>
                                <th>Packaging Type</th>
                                <th>Region</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @verbatim
                            <tr v-for="(rowItem, x) in tableData" v-bind:key="x">
                                <td>{{x + 1}}</td>
                                <td>{{rowItem.range_from}}</td>
                                <td>{{rowItem.range_to}}</td>
                                <td>{{rowItem.packaging_type.packaging_name}} | {{rowItem.packaging_type.name}} | Price: {{rowItem.packaging_type.unit_price}}</td>
                                <td>{{rowItem.region}}</td>
                                <td width="100px">
                                    <div class="btn-group dropdown">
                                        <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                            data-toggle="dropdown" type="button">
                                            Actions <i class="dropdown-caret"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a @click="ShowModal('EditShippingFeeType', JSON.stringify(rowItem))">Edit</a>
                                            </li>
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
                                @click="ShowModal('AddShippingFeeType')">{{ __('Create New') }}</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="shippingFeeTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	  <h4 class="modal-title" id="myModalLabel">@{{modalHeader}}</h4>
          </div>
          <div class="modal-body">
        
    		<div v-if="modalIntent == 'EditShippingFeeType' || modalIntent == 'AddShippingFeeType'">
    			<input type="hidden" class="form-control" v-model="modalData.product_id">
    			<div class="input-group">
    		        <span class="input-group-addon">From Points</span>
    		        <input type="number" class="form-control" v-model="modalData.range_from" placeholder="Qty">
    		    </div>
    			<br>
    			<div class="input-group">
    		        <span class="input-group-addon">To Points</span>
    		        <input type="number" class="form-control" v-model="modalData.range_to" placeholder="Qty">
    			</div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon">Packaging Type</span>
                    <select class="form-control" v-model="modalData.packaging_type_id" id="exampleFormControlSelect1">
                    <option disabled value="">Please select one</option>
                    <option v-for="(rowItem, z) in appView_packagingType.tableData" v-bind:key="z" v-bind:value="rowItem.id" >@{{rowItem.packaging_name}} | Size: @{{rowItem.name}} | Price: @{{rowItem.unit_price}}</option>
                    </select>
    			</div>
    			<br>
    			<div class="input-group">
                    <span class="input-group-addon">Region</span>
                    <select class="form-control" v-model="modalData.region" id="exampleFormControlSelect1">
                        <option disabled value="">Please select one</option>
                        <option value="NCR">NCR</option>
                        <option value="PRV">PROVINCIAL</option>
                        </select>
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

<div id="appView_packagingType">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Packaging Type</h3>
                    <div class="pull-right">
                        <span class="clickable filter" data-toggle="tooltip" title="Toggle table filter"
                            data-container="body">
                            <i class="glyphicon glyphicon-filter"></i>
                        </span>
                    </div>
                </div>
                <div class="panel-body">

                    <table class="table table-hover" id="dev-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Size</th>
                                <th>Unit Price</th>
                                <th>Length</th>
                                <th>Width</th>
                                <th>Height</th>
                                <th>Weight</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @verbatim
                            <tr v-for="(rowItem, x) in tableData" v-bind:key="x">
                                <td>{{x + 1}}</td>
                                <td>{{rowItem.packaging_name}}</td>
                                <td>{{rowItem.name}}</td>
                                <td>{{rowItem.unit_price}}</td>
                                <td>{{rowItem.length}}</td>
                                <td>{{rowItem.width}}</td>
                                <td>{{rowItem.height}}</td>
                                <td>{{rowItem.weight}}</td>
                                <td width="100px">
                                    <div class="btn-group dropdown">
                                        <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon"
                                            data-toggle="dropdown" type="button">
                                            Actions <i class="dropdown-caret"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a @click="ShowModal('EditPackagingType', JSON.stringify(rowItem))">Edit</a>
                                            </li>
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
                                @click="ShowModal('AddPackagingType')">{{ __('Create New') }}</button>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="modal fade" id="packagingTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    	  <h4 class="modal-title" id="myModalLabel">@{{modalHeader}}</h4>
          </div>
          <div class="modal-body">
        
    		<div v-if="modalIntent == 'EditPackagingType' || modalIntent == 'AddPackagingType'">
    			<input type="hidden" class="form-control" v-model="modalData.product_id">
    			<div class="input-group">
    		        <span class="input-group-addon">Name</span>
    		        <input type="text" class="form-control" v-model="modalData.packaging_name" placeholder="">
    		    </div>
    			<br>
    			<div class="input-group">
    		        <span class="input-group-addon">Size</span>
    		        <input type="text" class="form-control" v-model="modalData.name" placeholder="">
    			</div>
                <br>
                <div class="input-group">
    		        <span class="input-group-addon">Unit Price</span>
    		        <input type="text" class="form-control" v-model="modalData.unit_price" placeholder="">
    			</div>
    			<br>
    			<div class="input-group">
    		        <span class="input-group-addon">Length</span>
    		        <input type="text" class="form-control" v-model="modalData.length" placeholder="">
    		    </div>
    			<br>
    			<div class="input-group">
    		        <span class="input-group-addon">Width</span>
    		        <input type="text" class="form-control" v-model="modalData.width" placeholder="">
    		    </div>
    			<br>
    			<div class="input-group">
    		        <span class="input-group-addon">Height</span>
    		        <input type="text" class="form-control" v-model="modalData.height" placeholder="">
    		    </div>
    			<br>
    			<div class="input-group">
    		        <span class="input-group-addon">Weight</span>
    		        <input type="text" class="form-control" v-model="modalData.weight" placeholder="">
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

<script>
	    var appView_shippingFeeType = new Vue({
		    el: '#appView_shippingFeeType',
		    data: {
				message: 'Hello Vue!',
				modalIntent: '',
				modalHeader: '',
				productID: '',
				modalData: [],
		        tableData: []
		    },
		    methods: {
		        DispalyShippingFeeTypeData(url = '{{ route('shipping_settings.admin.shipping_fee_type') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                })
		                .then(data => {
							document.body.style.cursor = "default";
		                    this.tableData = data.data;
		                });
				},
		        UpdatePriceRangeData(x = this.modalData, url = '{{ route('shipping_settings.admin.shipping_fee_type.update') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x.id,
		                    range_from: x.range_from,
		                    range_to: x.range_to,
							packaging_type_id: x.packaging_type_id,
							region: x.region
		                })
		                .then(data => {
							document.body.style.cursor = "default";
							this.DispalyShippingFeeTypeData();
							$('#shippingFeeTypeModal').modal('hide');
		                });
				},
				 RemovePriceRangeData(x, url = '{{ route('shipping_settings.admin.shipping_fee_type.remove') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x.id
		                })
		                .then(data => {
							document.body.style.cursor = "default";
							this.DispalyShippingFeeTypeData();
		                });
				},
				ShowModal(x,y) {
					switch (x) {
						case 'EditShippingFeeType':
							this.modalHeader = "Edit Shipping Fee Type";
							this.modalIntent = "EditShippingFeeType";
							rwData = JSON.parse(y);
							this.modalData = rwData;
							break;
						case 'AddShippingFeeType':
							this.modalHeader = "New Shipping Fee Type";
							this.modalIntent = "AddShippingFeeType";
							this.modalData = [];
							break;
					
						default:
							break;
					}

				    $('#shippingFeeTypeModal').modal('show');
				}
		    },
		    created() {
				
            },
            mounted() {
                this.DispalyShippingFeeTypeData();
            }
        });
        
        var appView_packagingType = new Vue({
		    el: '#appView_packagingType',
		    data: {
				message: 'Hello Vue!',
				modalIntent: '',
				modalHeader: '',
				productID: '',
				modalData: [],
		        tableData: []
		    },
		    methods: {
		        DispalyPackagingTypeData(url = '{{ route('shipping_settings.admin.packaging_type') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                })
		                .then(data => {
							document.body.style.cursor = "default";
		                    this.tableData = data.data;
		                });
				},
		        UpdatePriceRangeData(x = this.modalData, url = '{{ route('shipping_settings.admin.packaging_type.update') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x.id,
		                    name: x.name,
		                    unit_price: x.unit_price,
							packaging_name: x.packaging_name,
							length: x.length,
							width: x.width,
							height: x.height,
							weight: x.weight
		                })
		                .then(data => {
							document.body.style.cursor = "default";
							this.DispalyPackagingTypeData();
                            $('#packagingTypeModal').modal('hide');
                            appView_shippingFeeType.DispalyShippingFeeTypeData();
		                });
				},
				 RemovePriceRangeData(x, url = '{{ route('shipping_settings.admin.packaging_type.remove') }}') {
					document.body.style.cursor = "progress";
		            axios
		                .post(url, {
		                    id: x.id
		                })
		                .then(data => {
							document.body.style.cursor = "default";
							this.DispalyPackagingTypeData();
		                });
				},
				ShowModal(x,y) {
					switch (x) {
						case 'EditPackagingType':
							this.modalHeader = "Edit Packaging Type";
							this.modalIntent = "EditPackagingType";
							rwData = JSON.parse(y);
							this.modalData = rwData;
							break;
						case 'AddPackagingType':
							this.modalHeader = "New Packaging Type";
							this.modalIntent = "AddPackagingType";
							this.modalData = [];
							break;
					
						default:
							break;
					}

				    $('#packagingTypeModal').modal('show');
				}
		    },
		    created() {
				
            },
            mounted() {
                this.DispalyPackagingTypeData();
            }
		})
</script>

@endsection
