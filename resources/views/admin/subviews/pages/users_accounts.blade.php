@extends('admin.main_layout')

@section('subview')

    <div class="panel panel-info">
        <div class="panel-heading">
            Accounts for Company >> {{$page_data->page_title}}
        </div>
        <div class="panel-body">
            <div class="col-md-12" style="    overflow-x: scroll;">

                <table id="cat_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>Username</td>
                        <td>Account Number</td>
                        <td>Account Balance</td>
                        <td>Referrer</td>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($users_accounts as $key => $users_account): ?>
                    <tr id="row<?= $users_account->id ?>">
                        <td><?= $key+1; ?></td>
                        <td>
                            <i class="fa fa-user"></i>
                            <b>{{$users_account->full_name}}</b> <br>
                            <b>{{$users_account->username}}</b>
                        </td>
                        <td>
                            {{$users_account->account_number}}
                        </td>
                        <td>
                            <span class="general_self_edit"
                                  data-url="<?= url("/general_self_edit") ?>"
                                  data-row_id="<?= $users_account->id ?>"
                                  data-tablename="App\models\pages\users_accounts_m"
                                  data-input_type="number"
                                  data-row_primary_col="id"
                                  data-field_name="account_balance"
                                  data-field_value="<?= $users_account->account_balance ?>"
                                  title="Click To Edit">
                                <?= $users_account->account_balance ?> <i class="fa fa-edit"></i>
                            </span>
                        </td>
                        <td>
                            <?php if(!empty($users_account->ref_full_name)): ?>
                                <label class="label label-info">
                                    <i class="fa fa-user"></i>
                                    <b>{{$users_account->ref_full_name}}</b>

                                </label>
                                <br>
                                <label class="label label-info">
                                    <b>{{$users_account->ref_username}}</b>
                                </label>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>



@endsection
