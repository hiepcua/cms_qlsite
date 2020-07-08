<?php
define('OBJ_PAGE','SITE_NEW');
// Init variables
$isAdmin=getInfo('isadmin');
if($isAdmin==1){
	$strWhere=" AND status=0 ";
	$get_q = isset($_GET['q']) ? antiData($_GET['q']) : '';
	$get_par = isset($_GET['par']) ? antiData($_GET['par']) : '';

	/*Gán strWhere*/
	if($get_q!=''){
		$strWhere.=" AND title LIKE '%".$get_q."%'";
	}
	if($get_par!=''){
		$strWhere.=" AND path LIKE '".$get_par."%'";
	}

	/*Begin pagging*/
	if(!isset($_SESSION['CUR_PAGE_'.OBJ_PAGE])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = 1;
	}
	if(isset($_POST['txtCurnpage'])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = (int)$_POST['txtCurnpage'];
	}

	$total_rows=SysCount('tbl_sites',$strWhere);
	$max_rows = 20;

	if($_SESSION['CUR_PAGE_'.OBJ_PAGE] > ceil($total_rows/$max_rows)){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = ceil($total_rows/$max_rows);
	}
	$cur_page=(int)$_SESSION['CUR_PAGE_'.OBJ_PAGE]>0 ? $_SESSION['CUR_PAGE_'.OBJ_PAGE] : 1;
	/*End pagging*/
	?>
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Chưa kích hoạt</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
						<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách trang</a></li>
						<li class="breadcrumb-item active">Chưa kích hoạt</li>
					</ol>
				</div><!-- /.col -->
			</div><!-- /.row -->
		</div><!-- /.container-fluid -->
	</div>
	<!-- /.content-header -->
	<!-- Main content -->
	<section class="content">
		<div class='container-fluid'>
			<div class="widget-frm-search">
				<form id='frm_search' method='get' action=''>
					<input type='hidden' id='txt_status' name='s' value='' />
					<div class='row'>
						<div class='col-sm-3'>
							<div class='form-group'>
								<input type='text' id='txt_title' name='q' value="<?php echo $get_q;?>" class='form-control' placeholder="Tiêu đề..." />
							</div>
						</div>
						<div class='col-sm-3'>
							<div class='form-group'>
								<select class="form-control" name="par" id="cbo_par">
									<option value="">-- Chọn nhóm --</option>
									<?php getListComboboxSites(0,0); ?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_par', <?php echo $get_par; ?>);
									});
								</script>
							</div>
						</div>
						<div class="col-sm-1"><input type="submit" name="" class="btn btn-primary" value="Tìm kiếm"></div>
					</div>
				</form>
			</div>
			<div class="card">
				<div class='table-responsive'>
					<table class="table">
						<thead>                  
							<tr>
								<th style="width: 10px">#</th>
								<th style="width: 10px">Trash</th>
								<th>Tiêu đề</th>
								<th>Tên miền</th>
								<th>Phone</th>
								<th>Email</th>
								<th>Ngày tạo</th>
								<th>Status</th>
								<th>Chi tiết</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($total_rows>0){
								$star = ($cur_page - 1) * $max_rows;
								$strWhere.=" ORDER BY cdate DESC LIMIT $star,".$max_rows;
								$obj=SysGetList('tbl_sites',array(), $strWhere, false);
								$stt=0;
								while($r=$obj->Fetch_Assoc()){
									$stt++;
									?>
									<tr>
										<td><?php echo $stt;?></td>
										<td align="center"><a href="<?php echo ROOTHOST.COMS.'/trash/'.$r['id'];?>" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fa fa-trash cred"></i></a></td>
										<td><?php echo $r['title'];?></td>
										<td><?php echo $r['domain'];?></td>
										<td><?php echo $r['phone'];?></td>
										<td><?php echo $r['email'];?></td>
										<td><?php echo date('d-m-Y H:i A', $r['cdate']);?></td>
										<td><?php echo $r['status'];?></td>
										<td align="center"><a href="<?php echo ROOTHOST.COMS.'/view/'.$r['id'];?>"><i class="fas fa-edit cblue"></i></a></td>
									</tr>
								<?php }
							}else{
								?>
								<tr>
									<td colspan='6' class='text-center'>Dữ liệu trống!</td>
								</tr>
							<?php }?>
						</tbody>
					</table>
				</div>
			</div>
			<nav class="d-flex justify-content-center">
				<?php 
				paging($total_rows,$max_rows,$cur_page);
				?>
			</nav>
		</div>
	</section>
<?php }else{
	echo "<h3 class='text-center'>You haven't permission</h3>";
}
?>