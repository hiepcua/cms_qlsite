<?php
define('OBJ_PAGE','GETLISTMEMBER');
$GetID = isset($_GET['id']) ? (int)antiData($_GET['id']) : 0;

function findChild($array, $id){
	$flag = 0;
	foreach ($array as $key => $value) {
		if($value['par_id'] == $id){
			$flag = 1;
			return $flag;
		}
	}
	return $flag;
};

function printChilds($array, $id){
	$str_childs='';
	foreach ($array as $key => $value) {
		if($value['par_id'] == $id){
			if(findChild($array, $value['id']) == 1){
				$str_childs.='<li class="item_tree parent_li"><span class="item_tree item" data-id="'.$value['id'].'"><a href="'.ROOTHOST.'user/getlist_user/'.$value['id'].'">'.$value['name'].'</a></span>';
				$str_childs.='<ul class="active">';
				$str_childs.= printChilds($array, $value['id']);
				$str_childs.='</ul>';
			}else{
				$str_childs.='<li class="item_tree"><span class="item_tree item" data-id="'.$value['id'].'"><a href="'.ROOTHOST.'user/getlist_user/'.$value['id'].'">'.$value['name'].'</a></span>';
			}

			$str_childs.='</li>';
		}
	}
	return $str_childs;
}
?>
<div class="row">
	<div class="col-sm-12 col-md-3 col-lg-3 sortable-grid ui-sortable">
		<div class="widget-tree">
			<header class="header">
				<span class="widget-icon"> <i class="fa fa-tree"></i> </span>
				<h2><a href="<?php echo ROOTHOST.COMS;?>" style="color:#fff;">Nhóm người dùng</a></h2>
				<a href="<?php echo ROOTHOST.'groupuser/add';?>" class="btn btn-success c-white f-right mr-2"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
			</header>
			<div id="widget_tree" class="tree smart-form">
				<ul role="group">
					<?php
					$arr_gusers = SysGetList('tbl_user_group', array(), '');
					$num_arr_gusers = count($arr_gusers);
					$str_tree='';
					for ($i=0; $i < $num_arr_gusers; $i++) { 
						if($arr_gusers[$i]['par_id'] == 0){
							$childs = false;
							$tmp_id = $arr_gusers[$i]['id'];
							echo '<li class="parent_li">
							<span class="root item" data-id="'.$tmp_id.'"><i class="fa fa-sitemap"></i><a href="'.ROOTHOST.'user/getlist_user/'.$tmp_id.'" class="a_root">'.$arr_gusers[$i]['name'].'</a></span>';

							if(findChild($arr_gusers, $tmp_id) == 1){
								echo '<ul class="active">';
								echo printChilds($arr_gusers, $tmp_id);
								echo '</ul>';
							}
							echo '</li>';
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-9 col-lg-9 sortable-grid ui-sortable">
		<?php
		$user=getInfo('username');
		$isAdmin=getInfo('isadmin');
		
		if($isAdmin==1 && $GetID!==0){
			$gusers = SysGetList('tbl_user_group', array(), " AND id=".$GetID);
			$guser = $gusers[0];

			$strWhere=" AND `group` = $GetID";
			$status = isset($_GET['s']) ? antiData($_GET['s']) : '';
			$q = isset($_GET['q']) ? antiData($_GET['q']) : '';
			/*Gán strwhere*/
			if($status != '' && $status == 'trash' ){
				$strWhere.=" AND `is_trash` = 1";
			}else{
				$strWhere.=" AND `is_trash` = 0";
			}
			if($q!=''){
				$strWhere.=" AND (`fullname` LIKE '%$q%' OR `email` LIKE '%$q%' OR  `phone` LIKE '%$q%') ";
			}
			/*Begin pagging*/
			if(!isset($_SESSION['CUR_PAGE_'.OBJ_PAGE])){
				$_SESSION['CUR_PAGE_'.OBJ_PAGE] = 1;
			}
			if(isset($_POST['txtCurnpage'])){
				$_SESSION['CUR_PAGE_'.OBJ_PAGE] = (int)$_POST['txtCurnpage'];
			}

			$total = SysCount('tbl_users', $strWhere);
			$total_rows = $total;
			$max_rows = 20;

			if($_SESSION['CUR_PAGE_'.OBJ_PAGE] > ceil($total_rows/$max_rows)){
				$_SESSION['CUR_PAGE_'.OBJ_PAGE] = ceil($total_rows/$max_rows);
			}
			$cur_page=(int)$_SESSION['CUR_PAGE_'.OBJ_PAGE]>0 ? $_SESSION['CUR_PAGE_'.OBJ_PAGE] : 1;
			/*End pagging*/
			?>
			<!-- Content Header (Page header) -->
			<div class="widget-tree" style="padding-left: 7px;">
				<header class="header">
					<h2>Danh sách người dùng thuộc nhóm <q><?php echo $guser['name'];?></q></h2>
				</header>
			</div>

			<!-- /.content-header -->
			<!-- Main content -->
			<section class="content">
				<div class='container-fluid'>
					<div class="card">
						<form id='frm_search' method='get' action=''><br/>
							<input type="hidden" id="txtids">
							<div class='col-sm-12'>
								<div class='row'>
									<div class='col-sm-5'>
										<div class='form-group'>
											<input type='text' id='txt_keyword' name='q' value='<?php echo $q;?>' class='form-control' placeholder='Tên, email hoặc số điện thoại'/>
											<input type='hidden' id='txt_status' name='s' value='<?php echo $status;?>' />
										</div>
									</div>
									<div class='col-sm-2'></div>
									<div class='col-sm-5 text-right'><div class='form-group'>
										<?php if($status=='trash'){?>
											<button type='button' class='btn btn-default' id='btn_list_member' ><i class="fas fa-list"></i> Danh sách </button>
										<?php }else{?>
											<button type='button' class='btn btn-default' id='btn_list_trash_member' ><i class="fas fa-trash"></i> Trash</button>
										<?php }?>
									</div></div>
								</div>
							</div>
							<script>
								$('#txt_keyword').keyup(function(e){
									if (e.which == 13) {
										/*Enter key pressed*/
										$('#frm_search').submit();
										e.preventDefault();
										return false;
									}
								});
								$('#cbo_room').change(function(){
									$('#frm_search').submit();
								})
								$('#btn_list_trash_member').click(function(){
									$('#txt_status').val('trash');
									$('#frm_search').submit();
								});
								$('#btn_list_member').click(function(){
									$('#txt_status').val('');
									$('#frm_search').submit();
								});
							</script>
						</form>
						<div class="table-responsive">
							<table class="table">
								<thead>                  
									<tr>
										<th style="width: 10px">
											<div class="custom-control custom-checkbox">
												<input class="custom-control-input chk_all" type="checkbox" id="chk_all" value="option1">
												<label for="chk_all" class="custom-control-label" style='margin-bottom: .5rem;'>&nbsp;</label>
											</div>
										</th>
										<th>Tên đăng nhập</th>
										<th>Tên đầy đủ</th>
										<th>Thông tin</th>
										<th class='text-center'>Admin</th>
										<th class='text-right'>
											<?php if($isAdmin=='1'){?>
												<button class='btn btn-primary' id='btn_add_member'><i class="fas fa-user-plus"></i></button>
											<?php }?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if($total>0){
										$start = ($cur_page - 1) * $max_rows;
										$strWhere.=" LIMIT $start,".$max_rows;
										$obj=SysGetList('tbl_users', array(), $strWhere, false);
										while($r=$obj->Fetch_Assoc()){
											?>
											<tr>
												<td>
													<div class="custom-control custom-checkbox">
														<input class="custom-control-input chk" type="checkbox" id="chk_<?php echo $r['username'];?>" name="chk" value="<?php echo $r['username'];?>">
														<label for="chk_<?php echo $r['username'];?>" name='chk' class="custom-control-label" style='margin-bottom: .5rem;'>&nbsp;</label>
													</div>
												</td>
												<td><?php echo $r['username'];?></td>
												<td><?php echo $r['fullname'];?></td>
												<td>
													<div class='user'><?php echo $r['email'];?></div>
													<div class='phone'><?php echo $r['phone'];?></div>
												</td>
												<td class='text-center'>
													<?php $checked=(int)$r['isadmin']!=1?"":"checked=true";?>
													<div class="custom-control custom-checkbox">
														<input class="custom-control-input chk_isadmin" <?php echo $checked;?> type="checkbox" id="chk_isadmin_<?php echo $r['username'];?>" value="<?php echo $r['username'];?>">
														<label for="chk_isadmin_<?php echo $r['username'];?>" class="custom-control-label" style='margin-bottom: .5rem;'>&nbsp;</label>
													</div>
												</td>
												<td class='text-right' style="min-width: 80px;">
													<i class="fas fa-edit btn_edit_member" data-username="<?php echo $r['username'];?>"></i>
													<i class="fas fa-trash btn_trash_member" data-username="<?php echo $r['username'];?>"></i>
												</td>
											</tr>
										<?php }
									}else{
										?>
										<tr>
											<td colspan='3' class='text-center'>Dữ liệu trống!</td>
										</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
						<nav class="d-flex justify-content-center">
							<?php paging($total_rows, $max_rows, $cur_page);?>
						</nav>
					</div>
				</div>
			</section>
			<script>
				$(document).ready(function(){
					$('.chk').click(function(){
						var flag=true;
						$('.chk').each(function(){
							if(!$(this).is(':checked')){flag=false; return;}
						});
						if(flag) $('.chk_all').attr('checked',true);
						else $('.chk_all').attr('checked',false);
					});
					$('.chk_all').click(function(){
						var ischeck=$(this).is(':checked');
						$('.chk').attr('checked',ischeck);
					});
					$('.chk_isadmin').click(function(){
						var ischeck = $(this).is(':checked')?'yes':'no';
						if(confirm('Bạn có chắc chắn thay đổi quyền của người dùng này?')){
							var _url="<?php echo ROOTHOST;?>ajaxs/user/change_isadmin.php";
							var _data={
								'user':$(this).val(),
								'ischeck':ischeck
							}
							$.post(_url,_data,function(req){
								/*alert('Change permission success!');*/
								window.location.reload();
							})
						}
					});
					$('#btn_add_member').click(function(){
						var _url="<?php echo ROOTHOST;?>ajaxs/user/frm_add.php";
						$.get(_url,function(req){
							$('#popup_modal .modal-body').html(req);
							$('#popup_modal').modal('show')
						});
					});
					$('.btn_edit_member').click(function(){
						var username = $(this).attr('data-username');
						var _url="<?php echo ROOTHOST;?>ajaxs/user/frm_edit.php";
						var _data={
							'user': username
						}
						$.post(_url, _data, function(req){
							$('#popup_modal .modal-body').html(req);
							$('#popup_modal').modal('show')
						});
					});
					$('.btn_trash_member').click(function(){
						var username = $(this).attr('data-username');
						var _url="<?php echo ROOTHOST;?>ajaxs/user/process_delete.php";
						var _data={
							'user': username
						}
						if (confirm('Bạn có chắc chắn muốn chuyển đến thùng rác?')) {
							$.post(_url, _data, function(req){
								/*console.log(req);*/
								if(req == 'success'){
									window.location.reload();
								}else{
									console.log('err');
								}
							});
						}
					});
				});
			</script>
		<?php }else{
			echo "<h3 class='text-center'>You haven't permission</h3>";
		}?>
	</div>
</div>