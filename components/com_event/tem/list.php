<?php
define('OBJ_PAGE','EVENTS');
// Init variables
$isAdmin=getInfo('isadmin');
if($isAdmin==1){
	$strWhere="";
	$get_q = isset($_GET['q']) ? antiData($_GET['q']) : '';

	/*Gán strWhere*/
	if($get_q!=''){
		$flg_search = 1;
		$strWhere.=" AND title LIKE '%".$get_q."%'";
	}

	/*Begin pagging*/
	if(!isset($_SESSION['CUR_PAGE_'.OBJ_PAGE])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = 1;
	}
	if(isset($_POST['txtCurnpage'])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = (int)$_POST['txtCurnpage'];
	}

	$total_rows=SysCount('tbl_events',$strWhere);
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
					<h1 class="m-0 text-dark">Danh sách sự kiện</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
						<li class="breadcrumb-item active">Danh sách sự kiện</li>
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
					<div class='row'>
						<div class='col-sm-3'>
							<div class='form-group'>
								<input type='text' id='txt_title' name='q' value="<?php echo $get_q;?>" class='form-control' placeholder="Tiêu đề..." />
							</div>
						</div>
						<div class="col-sm-7"></div>
						<div class="col-sm-2">
							<a href="<?php echo ROOTHOST.COMS;?>/add" class="btn btn-primary float-sm-right"><i class="fa fa-plus-circle" aria-hidden="true"></i> Thêm mới</a>
						</div>
					</div>
				</form>
			</div>
			<div class="card">
				<div class='table-responsive'>
					<table class="table">
						<thead>                  
							<tr>
								<th style="width: 10px">#</th>
								<th>Xóa</th>
								<th>Tiêu đề</th>
								<th>Chuyên mục</th>
								<th>Mô tả</th>
								<th style="text-align: center;">Hot</th>
								<th style="text-align: center;">Hiển thị</th>
								<th style="text-align: center;">Chi tiết</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($total_rows>0){
								$star = ($cur_page - 1) * $max_rows;
								$strWhere.=" LIMIT $star,".$max_rows;
								$res_events = SysGetList('tbl_events', array(), $strWhere, false);
								$i=0;
								while($rows = $res_events->Fetch_Assoc()){
									$i++;
									$ids=$rows['id'];
									$title = $rows['title'];
									$intro = Substring(stripslashes($rows['title']),0,10);

									$res_cates = SysGetList('tbl_categories', array(), "AND id=".$rows['cate_id']);
									$cate_title = isset($res_cates[0]['title']) ? $res_cates[0]['title'] : '<i>(N/A)</i>';

									if($rows['ishot'] == 1) 
										$icon_ishot    = "<i class='fas fa-toggle-on cgreen'></i>";
									else $icon_ishot   = '<i class="fa fa-toggle-off cgray" aria-hidden="true"></i>';

									if($rows['isactive'] == 1) 
										$icon_active    = "<i class='fas fa-toggle-on cgreen'></i>";
									else $icon_active   = '<i class="fa fa-toggle-off cgray" aria-hidden="true"></i>';

									echo "<tr name='trow'>";
									echo "<td width='30' align='center'>".$i."</td>";

									echo "<td align='center' width='10'><a href='".ROOTHOST.COMS."/delete/".$ids."' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'><i class='fa fa-trash cred' aria-hidden='true'></i></a></td>";

									echo "<td>".$title."</td>";
									echo "<td>".$cate_title."</td>";
									echo "<td>".$intro."</td>";

									echo "<td align='center'>";
									echo "<a href='".ROOTHOST.COMS."/ishot/".$ids."'>";
									echo $icon_ishot;
									echo "</a></td>";

									echo "<td align='center'>";
									echo "<a href='".ROOTHOST.COMS."/active/".$ids."'>";
									echo $icon_active;
									echo "</a></td>";

									echo "<td align='center'>";
									echo "<a href='".ROOTHOST.COMS."/edit/".$ids."'>";
									echo "<i class='fa fa-edit' aria-hidden='true'></i>";
									echo "</a></td>";
									echo "</tr>";
								}
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