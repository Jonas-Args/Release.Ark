<?php $__env->startSection('content'); ?>

<div class="row">

    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><?php echo e(__('System Default Currency')); ?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="<?php echo e(route('business_settings.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('System Default Currency')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control demo-select2-placeholder" name="system_default_currency">
                                <?php $__currentLoopData = $active_currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($currency->id); ?>" <?php if(\App\BusinessSetting::where('type', 'system_default_currency')->first()->value == $currency->id) echo 'selected'?> ><?php echo e($currency->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <input type="hidden" name="types[]" value="system_default_currency">
                        <div class="col-lg-3">
                            <button class="btn btn-purple" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><?php echo e(__('Set Currency Formats')); ?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="<?php echo e(route('business_settings.update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="symbol_format">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('Symbol Format')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control demo-select2-placeholder" name="symbol_format">
                                <option value="1">[Symbol] [Amount]</option>
                                <option value="2">[Amount] [Symbol]</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="types[]" value="no_of_decimals">
                        <div class="col-lg-3">
                            <label class="control-label"><?php echo e(__('No of decimals')); ?></label>
                        </div>
                        <div class="col-lg-6">
                            <select class="form-control demo-select2-placeholder" name="no_of_decimals">
                                <option value="0">12345</option>
                                <option value="1">1234.5</option>
                                <option value="2">123.45</option>
                                <option value="3">12.345</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-12 text-right">
                            <button class="btn btn-purple" type="submit"><?php echo e(__('Save')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <a onclick="currency_modal()" class="btn btn-rounded btn-info pull-right"><?php echo e(__('Add New Currency')); ?></a>
    </div>
</div>

<br>

<div class="row">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo e(__('All Currency')); ?></h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-bordered demo-dt-basic" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo e(__('Currency name')); ?></th>
                        <th><?php echo e(__('Currency symbol')); ?></th>
                        <th><?php echo e(__('Currency code')); ?></th>
                        <th><?php echo e(__('Exchange rate')); ?>(1 USD = ?)</th>
                        <th><?php echo e(__('Status')); ?></th>
                        <th width="10%"><?php echo e(__('Options')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+1); ?></td>
                            <td><?php echo e($currency->name); ?></td>
                            <td><?php echo e($currency->symbol); ?></td>
                            <td><?php echo e($currency->code); ?></td>
                            <td><?php echo e($currency->exchange_rate); ?></td>
                            <td>
                                <label class="switch">
                                    <input onchange="update_currency_status(this)" value="<?php echo e($currency->id); ?>" type="checkbox" <?php if($currency->status == 1) echo "checked";?> >
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-icon" data-toggle="dropdown" type="button">
                                        <?php echo e(__('Actions')); ?> <i class="dropdown-caret"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a onclick="edit_currency_modal('<?php echo e($currency->id); ?>');"><?php echo e(__('Edit')); ?></a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
    </div>
</div>

<div class="modal fade" id="add_currency_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>

<div class="modal fade" id="currency_modal_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script type="text/javascript">

        //Updates default currencies
        // function updateCurrency(i){
        //     var exchange_rate = $('#exchange_rate_'+i).val();
        //     if($('#status_'+i).is(':checked')){
        //         var status = 1;
        //     }
        //     else{
        //         var status = 0;
        //     }
        //     $.post('<?php echo e(route('currency.update')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:i, exchange_rate:exchange_rate, status:status}, function(data){
        //         location.reload();
        //     });
        // }
        //
        // //Updates your currency
        // function updateYourCurrency(i){
        //     var name = $('#name_'+i).val();
        //     var symbol = $('#symbol_'+i).val();
        //     var code = $('#code_'+i).val();
        //     var exchange_rate = $('#exchange_rate_'+i).val();
        //     if($('#status_'+i).is(':checked')){
        //         var status = 1;
        //     }
        //     else{
        //         var status = 0;
        //     }
        //     $.post('<?php echo e(route('your_currency.update')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:i, name:name, symbol:symbol, code:code, exchange_rate:exchange_rate, status:status}, function(data){
        //         location.reload();
        //     });
        // }

        function currency_modal(){
            $.get('<?php echo e(route('currency.create')); ?>',function(data){
                $('#modal-content').html(data);
                $('#add_currency_modal').modal('show');
            });
        }

        function update_currency_status(el){
            if(el.checked){
                var status = 1;
            }
            else{
                var status = 0;
            }
            $.post('<?php echo e(route('currency.update_status')); ?>', {_token:'<?php echo e(csrf_token()); ?>', id:el.value, status:status}, function(data){
                if(data == 1){
                    showAlert('success', 'Currency Status updated successfully');
                }
                else{
                    showAlert('danger', 'Something went wrong');
                }
            });
        }

        function edit_currency_modal(id){
            $.post('<?php echo e(route('currency.edit')); ?>',{_token:'<?php echo e(@csrf_token()); ?>', id:id}, function(data){
                $('#currency_modal_edit .modal-content').html(data);
                $('#currency_modal_edit').modal('show', {backdrop: 'static'});
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\PHP\Ark\resources\views/business_settings/currency.blade.php ENDPATH**/ ?>