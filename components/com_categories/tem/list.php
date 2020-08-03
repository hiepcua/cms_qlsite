<?php
define('OBJ_PAGE','CATEGORIES');
// Init variables
$isAdmin=getInfo('isadmin');
$flg_search = 0;
function listTable($strwhere="",$parid=0,$level=0,$rowcount, $search=0){
	if($search == 0){
		$sql="SELECT * FROM tbl_categories WHERE 1=1 AND par_id=$parid $strwhere";
	}else{
		$sql="SELECT * FROM tbl_categories WHERE 1=1 $strwhere";
	}
	$objdata=new CLS_MYSQL();
	$objdata->Query($sql);
	$str_space="";
	if($level!=0){  
		for($i=0;$i<$level;$i++)
			$str_space.="|----- ";
	}
	while($rows=$objdata->Fetch_Assoc()){
		$ids=$rows['id'];
		$title=Substring(stripslashes($rows['title']),0,10);

		if($rows['isactive'] == 1) 
			$icon_active    = "<i class='fas fa-toggle-on cgreen'></i>";
		else $icon_active   = '<i class="fa fa-toggle-off cgray" aria-hidden="true"></i>';

		$par_name = SysGetList('tbl_categories', array('title'), "AND id=".$rows['par_id']);
		$par_name = isset($par_name[0]['title']) ? $par_name[0]['title'] : '';

		$site_name = SysGetList('tbl_sites', array('domain'), "AND id=".$rows['site_id']);
		$site_name = isset($site_name[0]['domain']) ? $site_name[0]['domain'] : '';

		echo "<tr name='trow'>";
		
		echo "<td align='center' width='10'><a href='".ROOTHOST.COMS."/delete/".$ids."' onclick='return confirm(\"Bạn có chắc muốn xóa?\")'><i class='fa fa-trash cred' aria-hidden='true'></i></a></td>";

		echo "<td>".$str_space.$title."</td>";
		echo "<td>".$par_name."</td>";
		echo "<td>".$site_name."</td>";
		echo "<td>".Substring($rows['intro'], 0, 10)."</td>";

		echo "<td align='center'>";
		echo "<a href='".ROOTHOST.COMS."/active/".$ids."'>";
		echo $icon_active;
		echo "</a></td>";
		echo "<td align='center'>";
		echo "<a href='".ROOTHOST.COMS."/edit/".$ids."'>";
		echo "<i class='fa fa-edit' aria-hidden='true'></i>";
		echo "</a></td>";
		echo "</tr>";
		$nextlevel=$level+1;
		listTable($strwhere,$ids,$nextlevel,$rowcount);
	}
}
function getListComboboxSites($parid=0, $level=0, $childs=array()){
	$sql="SELECT * FROM tbl_sites WHERE `par_id`='$parid' AND `isactive`='1' ";
	$objdata=new CLS_MYSQL();
	$objdata->Query($sql);
	$char="";
	if($level!=0){
		for($i=0;$i<$level;$i++)
			$char.="|-----";
	}
	if($objdata->Num_rows()<=0) return;
	while($rows=$objdata->Fetch_Assoc()){
		$id=$rows['id'];
		$parid=$rows['par_id'];
		$title=$rows['title'];
		if(in_array($id, $childs)){
			echo "<option value='$id' disabled='true' class='disabled'>$char $title</option>";
		}else{
			echo "<option value='$id'>$char $title</option>";
		}
		$nextlevel=$level+1;
		getListComboboxSites($id,$nextlevel, $childs);
	}
}
if($isAdmin==1){
	$strWhere="";
	$get_q = isset($_GET['q']) ? antiData($_GET['q']) : '';
	$get_site = isset($_GET['s']) ? antiData($_GET['s']) : '';

	/*Gán strWhere*/
	if($get_q!=''){
		$flg_search = 1;
		$strWhere.=" AND title LIKE '%".$get_q."%'";
	}
	if($get_site!=''){
		$strWhere.=" AND site_id=".$get_site;
	}

	/*Begin pagging*/
	if(!isset($_SESSION['CUR_PAGE_'.OBJ_PAGE])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = 1;
	}
	if(isset($_POST['txtCurnpage'])){
		$_SESSION['CUR_PAGE_'.OBJ_PAGE] = (int)$_POST['txtCurnpage'];
	}

	$total_rows=SysCount('tbl_categories',$strWhere);
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
					<h1 class="m-0 text-dark">Danh sách chuyên mục</h1>
				</div><!-- /.col -->
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?php echo ROOTHOST;?>">Bảng điều khiển</a></li>
						<li class="breadcrumb-item active">Danh sách chuyên mục</li>
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
								<input type='text' id='txt_title' name='q' class='form-control' placeholder="Tên chuyên mục..." />
							</div>
						</div>
						<div class='col-sm-3'>
							<div class='form-group'>
								<select class="form-control" name="s" id="cbo_site">
									<option value="">-- Chọn trang --</option>
									<?php getListComboboxSites(0,0); ?>
								</select>
								<script type="text/javascript">
									$(document).ready(function(){
										cbo_Selected('cbo_site', <?php echo $get_site; ?>);
									});
								</script>
							</div>
						</div>
						<div class="col-sm-1"><input type="submit" name="" class="btn btn-primary" value="Tìm kiếm"></div>
						<div class="col-sm-3"></div>
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
								<th>Xóa</th>
								<th>Tên chuyên mục</th>
								<th>Chuyên mục cha</th>
								<th>Trang</th>
								<th>Mô tả</th>
								<th style="text-align: center;" width="80px">Hiển thị</th>
								<th style="text-align: center;" width="80px">Sửa</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($total_rows>0){
								$star = ($cur_page - 1) * $max_rows;
								$strWhere.=" LIMIT $star,".$max_rows;

								if($flg_search !== 0){
									listTable($strWhere,0,0,0, 1);
								}else{
									listTable($strWhere,0,0,0);
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