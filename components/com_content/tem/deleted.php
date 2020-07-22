<?php
define('OBJ_PAGE','VOD_DELETE');
// Init variables
$user=getInfo('username');
$isAdmin=getInfo('isadmin');
if($isAdmin==1){
	$strWhere=" AND is_trash=1 ";

	$get_s = isset($_GET['s']) ? antiData($_GET['s']) : '';
	$get_q = isset($_GET['q']) ? antiData($_GET['q']) : '';
	$get_cate = isset($_GET['cate']) ? (int)antiData($_GET['cate']) : 0;

	/*Gán strWhere*/
	if($get_s!=''){
		$strWhere.=" AND status =".$get_s;
	}
	if($get_q!=''){
		$strWhere.=" AND title LIKE '%".$get_q."%'";
	}
	if($get_cate!=0){
		$strWhere.=" AND cat_id=".$get_cate;
	}

	/*Begin pagging*/
	if(!isset($_SESSION['CUR_PAGE_'.OBJ_PAGE])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = 1;
	}
	if(isset($_POST['txtCurnpage'])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = (int)$_POST['txtCurnpage'];
	}

	$total_rows=SysCount('tbl_content',$strWhere);
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
					<h1 class="m-0 text-dark">Danh sách bài viết đã xóa</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
						<li class="breadcrumb-item"><a href="<?php echo ROOTHOST.COMS;?>">Danh sách bài viết</a></li>
						<li class="breadcrumb-item active">Danh sách bài viết đã xóa</li>
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
								<select class="form-control" name="cate" id="cbo_cate">
									<option  value="">-- Chọn nhóm --</option>
									<?php getListComboboxCategories(0,0); ?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_cate', <?php echo $get_cate; ?>);
									});
								</script>
							</div>
						</div>
						<div class='col-sm-3'>
							<div class='form-group'>
								<select class="form-control" name="s" id="cbo_status">
									<option value="">-- Status --</option>
									<option value="0">Đang biên tập</option>
									<option value="1">Chờ duyệt</option>
									<option value="2">Trả về</option>
									<option value="3">Chờ xuất bản</option>
									<option value="4">Đã xuất bản</option>
									<option value="5">Bị gỡ xuống</option>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_status', <?php echo $get_s;?>);
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
								<th>Tiêu đề</th>
								<th>Nhóm</th>
								<th>Album</th>
								<th>Kênh</th>
								<th>Type</th>
								<th>Ngày tạo</th>
								<th class="text-center">Chi tiết</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($total_rows>0){
								$star = ($cur_page - 1) * $max_rows;
								$strWhere.=" LIMIT $star,".$max_rows;
								$obj=SysGetList('tbl_content',array(), $strWhere, false);
								$stt=0;
								while($r=$obj->Fetch_Assoc()){
									$stt++;
									$cates = SysGetList('tbl_categories', array('title'), ' AND id='.$r['cat_id']);
									$cate = count($cates)>0 ? $cates[0] : [];
									$cate_title = isset($cate['title']) ? $cate['title'] : '<i>N/A</i>';

									$events = SysGetList('tbl_channels', array('title'), ' AND id='.$r['event_id']);
									$event = count($events)>0 ? $events[0] : [];
									$event_title = isset($event['title']) ? $event['title'] : '<i>N/A</i>';

									switch ($r['type']) {
										case 1:
										$type = 'Video';
										break;
										case 2:
										$type = 'Audio';
										break;
										case 3:
										$type = 'Text';
										break;
										default:
										$type = 'Text';
										break;
									}
									?>
									<tr>
										<td><?php echo $stt;?></td>
										<td><?php echo $r['title'];?></td>
										<td><?php echo $cate_title;?></td>
										<td><?php echo $r['album_id'];?></td>
										<td><?php echo $event_title;?></td>
										<td><?php echo $type;?></td>
										<td><?php echo date('d-m-Y H:i A', $r['cdate']);?></td>
										<td class="text-center"><a href="<?php echo ROOTHOST.COMS.'/edit/'.$r['id'];?>"><i class="fas fa-edit cblue"></i></a></td>
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