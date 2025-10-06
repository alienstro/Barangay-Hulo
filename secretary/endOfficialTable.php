<?php 

include_once '../connection.php';


try{


  $position = $con->real_escape_string($_REQUEST['position']);

  // We'll return all officials by combining active (official_status) and ended (official_end_status)
  // Each SELECT must return the same columns so they can be UNIONed together.
  $selects = [];

  $selects[] = "SELECT s.position, s.voters, s.status, info.official_id, info.first_name, info.middle_name, info.last_name, info.image, info.image_path, s.single_parent, s.pwd_info, position.color, position.position as official_position, s.term_to FROM official_status s INNER JOIN official_information info ON s.official_id = info.official_id INNER JOIN position ON s.position = position.position_id";

  $selects[] = "SELECT es.position, es.voters, es.status, einfo.official_id, einfo.first_name, einfo.middle_name, einfo.last_name, einfo.image, einfo.image_path, es.single_parent, es.pwd_info, position.color, position.position as official_position, es.term_to FROM official_end_status es INNER JOIN official_end_information einfo ON es.official_id = einfo.official_id INNER JOIN position ON es.position = position.position_id";

  // If position filter is provided, add it to each select
  if(!empty($position)){
    for($i=0;$i<count($selects);$i++){
      if(strpos($selects[$i], 'FROM official_status s') !== false){
        $selects[$i] .= " WHERE s.position='".$position."'";
      }else{
        $selects[$i] .= " WHERE es.position='".$position."'";
      }
    }
  }

  // Combine
  $baseSql = implode(' UNION ALL ', $selects);

  // Get total records (without filtering)
  $countSql = "SELECT COUNT(*) as cnt FROM (".$baseSql.") as t";
  $stmt = $con->prepare($countSql) or die ($con->error);
  $stmt->execute();
  $resCount = $stmt->get_result()->fetch_assoc();
  $totalData = intval($resCount['cnt']);

  // Apply search filter if present
  $whereSearch = '';
  if(isset($_REQUEST['search']['value']) && $_REQUEST['search']['value'] != ''){
    $search = $con->real_escape_string($_REQUEST['search']['value']);
    $whereSearch = " WHERE (t.first_name LIKE '%".$search."%' OR t.last_name LIKE '%".$search."%' OR t.official_id LIKE '%".$search."%' OR t.status LIKE '%".$search."%') ";
  }

  // Count after filtering
  $countFilteredSql = "SELECT COUNT(*) as cnt FROM (".$baseSql.") as t ". $whereSearch;
  $stmt = $con->prepare($countFilteredSql) or die ($con->error);
  $stmt->execute();
  $resFiltered = $stmt->get_result()->fetch_assoc();
  $totalFiltered = intval($resFiltered['cnt']);

  // Build final data query with optional ordering and limit
  $dataSql = "SELECT * FROM (".$baseSql.") as t ". $whereSearch;

  // Map DataTable column index to actual column names in derived table 't'
  $columns = [
    0 => 'image',
    1 => 'official_position',
    2 => 'official_id',
    3 => 'last_name',
    4 => 'pwd_info',
    5 => 'single_parent',
    6 => 'voters',
    7 => 'term_to',
    8 => 'status',
    9 => 'official_id'
  ];

  if(isset($_REQUEST['order'])){
    $colIndex = intval($_REQUEST['order']['0']['column']);
    $colDir = $_REQUEST['order']['0']['dir'] === 'asc' ? 'ASC' : 'DESC';
    $orderBy = isset($columns[$colIndex]) ? $columns[$colIndex] : 'position';
    $dataSql .= ' ORDER BY t.'.$orderBy.' '.$colDir.' ';
  } else {
    $dataSql .= ' ORDER BY t.position DESC ';
  }

  if($_REQUEST['length'] != -1){
    $start = intval($_REQUEST['start']);
    $length = intval($_REQUEST['length']);
    $dataSql .= ' LIMIT '.$start.' , '.$length.' ';
  }

  $stmt = $con->prepare($dataSql) or die ($con->error);
  $stmt->execute();
  $result = $stmt->get_result();
  $data = [];


  while($row = $result->fetch_assoc()){
    if($row['image'] != '' || $row['image'] != null || !empty($row['image'])){
      $image = '<span style="cursor: pointer;" class="pop"><img src="'.$row['image_path'].'" alt="residence_image" class="img-circle" width="40"></span>';
    }else{
      $image = '<span style="cursor: pointer;" class="pop"><img src="../assets/dist/img/blank_image.png" alt="residence_image" class="img-circle"  width="40"></span>';
    }


    if($row['voters'] == 'YES'){
      $voters = '<span class="badge badge-success text-md">'.$row['voters'].'</span>';
    }else{
      $voters = '<span class="badge badge-danger text-md">'.$row['voters'].'</span>';
    }
  
  
    if($row['middle_name'] != ''){
      $middle_name = ucfirst($row['middle_name'])[0].'.';
    }else{
      $middle_name = '';
    }
    if($row['single_parent'] == 'YES'){
      $single_parent = '<span class="badge badge-info text-md ">'.$row['single_parent'].'</span>';
    }else{
      $single_parent = '<span class="badge badge-warning text-md ">'.$row['single_parent'].'</span>';
    }


    if($row['status'] == 'ACTIVE'){
      $status = '<label class="switch">
                      <input type="checkbox" class="editStatus" data-status="ACTIVE"  id="'.$row['official_id'].'"  checked>
                    <div class="slider round">
                      <span class="on ">ACTIVE</span>
                      <span class="off ">INACTIVE</span>
                    </div>
                </label>';
  }else{
      $status = '<label class="switch">
                      <input type="checkbox" class="editStatus" id="'.$row['official_id'].'" data-status="INACTIVE">
                    <div class="slider round">
                      <span class="off ">INACTIVE</span>
                      <span class="on ">ACTIVE</span>
                    </div>
                </label> ';
  }
  
  $subdata = [];
  $subdata[] = $image;
  $subdata[] = '<span class="badge" style="background-color: '.$row['color'].'">'.$row['official_position'].'</span>';
  $subdata[] = $row['official_id'];
  $subdata[] =  ucfirst($row['first_name']).' '. $middle_name .' '. ucfirst($row['last_name']); 

  $subdata[] = $row['pwd_info'];
  $subdata[] = $single_parent;
  $subdata[] = $voters;
  // Term To column (display raw term_to as stored)
  $subdata[] = $row['term_to'];
  $subdata[] = $status;
  $subdata[] = '<a href="viewEndOfficial.php?request='.$row['official_id'].'" style="cursor: pointer;  color: yellow;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-user-edit text-lg px-3 "></a>
  <i style="cursor: pointer;  color: red;  text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;" class="fa fa-times text-lg px-2 deleteOfficial" id="'.$row['official_id'].'"></i>';
  $data[] = $subdata;
  }


  $json_data = [
    'draw' => intval($_REQUEST['draw']),
    'recordsTotal' => intval($totalData),
    'recordsFiltered' => intval($totalFiltered),
    'data' => $data,
    'total' => number_format($totalData),
  ];

  echo json_encode($json_data);


}catch(Exception $e){
  echo $e->getMessage();
}




?>