@extends('layouts.app')

@section('content')

    <div class="col-md-offset-2 col-md-8">
        <div class="panel">
            <!--Panel heading-->
            <div class="panel-heading">
                <h3 class="panel-title">Membership Sales Report</h3>
            </div>

            <!--Panel body-->
            <div class="panel-body" style="max-height: 700px; overflow-y:auto">
                <div class="table-responsive" id="appViewPort_report_list">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>User</th>
                                <th>Activation Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr  v-for="(rowItem, x) in priceRange" v-bind:key="x">
                                <td>@{{ rowItem.businessPackage.packageName }}</td>
                                <td>@{{ rowItem.userAuth.userInfo.firstName }} @{{ rowItem.userAuth.userInfo.lastName }}</td>
                                <td>@{{ rowItem.activationDate | formatDate}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<hr>
    <div class="col-md-offset-2 col-md-8">
        <div class="panel">
            <!--Panel heading-->
            <div class="panel-heading">
                <h3 class="panel-title">Membership Sales Report</h3>
            </div>

            <!--Panel body-->
            <div class="panel-body" style="max-height: 700px; overflow-y:auto">
                <div class="table-responsive" id="appViewPort_report">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Package Name</th>
                                <th>No. of Sale</th>
                                <th>Sales Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr  v-for="(rowItem, x) in priceRange" v-bind:key="x">
                                <td>@{{ rowItem.businessPackage.packageName }}</td>
                                <td>@{{ rowItem.totalSales }}</td>
                                <td>PHP @{{ rowItem.totalAmount }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><b>Total: @{{totalSales}}</b></td>
                                <td><b>Total: @{{total}}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<script>
         var appView_report = new Vue({
		    el: '#appViewPort_report',
		        data: {
		            modalData: [],
		            priceRange: [],
                },
                computed: {
                    total: function () {
                        return this.priceRange.reduce(function (total, item) {
                            return total + item.totalAmount;
                        }, 0);
                    },
                    totalSales: function () {
                        return this.priceRange.reduce(function (total, item) {
                            return total + item.totalSales;
                        }, 0);
                    }
                },
		    methods: {
		        DispalyPriceRangeData(x, url = '{{ route('stock_report.membership') }}') {
					this.productID = x;
					document.body.style.cursor = "progress";
		            axios
		                .get(url, {
		                    id: x
		                })
		                .then(data => {
							document.body.style.cursor = "default";
                            this.priceRange = data.data;
                            setTimeout(this.DispalyPriceRangeData, 10000);
		                });
				}
		    },
		    created() {
		    },
            mounted() {
                this.DispalyPriceRangeData();
            }
        });
        
        var appView_report_list = new Vue({
		    el: '#appViewPort_report_list',
		        data: {
		            modalData: [],
		            priceRange: [],
                },
                computed: {
                    total: function () {
                        return this.priceRange.reduce(function (total, item) {
                            return total + item.totalAmount;
                        }, 0);
                    },
                    totalSales: function () {
                        return this.priceRange.reduce(function (total, item) {
                            return total + item.totalSales;
                        }, 0);
                    }
                },
		    methods: {
		        DispalyPriceRangeData(x, url = '{{ route('stock_report.membership_list') }}') {
					this.productID = x;
					document.body.style.cursor = "progress";
		            axios
		                .get(url, {
		                    id: x
		                })
		                .then(data => {
							document.body.style.cursor = "default";
                            this.priceRange = data.data;
                            setTimeout(this.DispalyPriceRangeData, 10000);
		                });
				}
		    },
		    created() {
		    },
            mounted() {
                this.DispalyPriceRangeData();
            },
              filters: {
    columnHead(value) {
      return value
        .split("_")
        .join(" ")
        .toUpperCase();
    },
    formatDate(value) {
      if (value) {
        moment.defaultFormat = "HH:mm:ss";
        return moment(String(value)).format('MMMM Do YYYY, h:mm:ss a');
      }
    },
    formatWeekDays(value, weekDay) {
      if (value) {
        value = value.replace("1", "Mon");
        value = value.replace("2", "Tue");
        value = value.replace("3", "Wed");
        value = value.replace("4", "Thu");
        value = value.replace("5", "Fri");
        value = value.replace("6", "Sat");
        value = value.replace("7", "Sun");

        if (weekDay != "") {
          var weekday = new Array(7);
          weekday["Monday"] = "M";
          weekday["Tuesday"] = "T";
          weekday["Wednesday"] = "W";
          weekday["Thursday"] = "TH";
          weekday["Friday"] = "F";
          weekday["Saturday"] = "S";
          weekday["Sunday"] = "U";

          var qr = weekday[weekDay];
          if (value.includes(qr)) {
            value = weekDay;
            value = "Open on <br> " + value;
          } else {
            value = "Also Open on <br> " + value;
          }
        } else {
          value = "Open on <br> " + value;
        }

        return value;
      }
    }
  }
		});
</script>

@endsection
