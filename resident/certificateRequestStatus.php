<?php 



include_once '../connection.php';

try{


  if(isset($_REQUEST['residence_id']) && isset($_REQUEST['certificate_id'])){

    $residence_id = $con->real_escape_string($_REQUEST['residence_id']);
    $certificate_id = $con->real_escape_string($_REQUEST['certificate_id']);

    $sql_request_status = "SELECT certificate_request.*, residence_information.first_name, residence_information.middle_name, residence_information.last_name,
    residence_information.image, residence_information.image_path, residence_information.address,  residence_information.gender, residence_information.age,  residence_information.contact_number
    FROM certificate_request INNER JOIN residence_information ON certificate_request.residence_id = residence_information.residence_id WHERE certificate_request.id = ?
    AND certificate_request.residence_id = ?";
    $stmt_request_status = $con->prepare($sql_request_status) or die ($con->error);
    $stmt_request_status->bind_param('ss',$certificate_id,$residence_id);
    $stmt_request_status->execute();
    $result =  $stmt_request_status->get_result();

    $row_request_status = $result->fetch_assoc();
        
    if($row_request_status['image'] != '' || $row_request_status['image'] != null){
      $image = '<img class="img-circle elevation-2" src="'.$row_request_status['image_path'].'" alt="User Avatar">';
    }else{
      $image = '<img class="img-circle elevation-2" src="../assets/dist/img/blank_image.png" alt="User Avatar">';
    }
    

  }




}catch(Exception $e){
  echo $e->getMessage();
}


?>


<!-- Modal -->
<style>
  #showStatusRequestModal .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
  }

  #showStatusRequestModal .modal-header {
    background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
    border: none;
    padding: 24px 28px;
    border-radius: 20px 20px 0 0;
  }

  #showStatusRequestModal .modal-title {
    color: white;
    font-weight: 700;
    font-size: 22px;
    letter-spacing: 0.5px;
  }

  #showStatusRequestModal .close {
    color: white;
    opacity: 1;
    text-shadow: none;
    font-size: 32px;
    font-weight: 300;
    transition: all 0.3s ease;
  }

  #showStatusRequestModal .close:hover {
    transform: rotate(90deg);
    opacity: 0.8;
  }

  #showStatusRequestModal .modal-body {
    padding: 0;
    background: #f8f9fa;
  }

  .profile-card-modern {
    background: white;
    border-radius: 0;
    overflow: hidden;
    box-shadow: none;
    margin: 0;
  }

  .profile-header-modern {
     background: linear-gradient(135deg, rgba(179, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.7) 100%);
    padding: 40px 30px;
    text-align: center;
    position: relative;
  }

  @keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .profile-image-modern {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 5px solid white;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    object-fit: cover;
    margin: 0 auto 20px;
    position: relative;
    z-index: 1;
    animation: float 3s ease-in-out infinite;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
  }

  .profile-name-modern {
    color: white;
    font-size: 26px;
    font-weight: 700;
    margin: 0;
    position: relative;
    z-index: 1;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
  }

  .profile-details-modern {
    padding: 30px;
    background: white;
  }

  .detail-item-modern {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    margin-bottom: 12px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    border-left: 4px solid #b30000;
  }

  .detail-item-modern:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background: #fff;
  }

  .detail-icon-modern {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 16px;
    flex-shrink: 0;
  }

  .detail-icon-modern i {
    color: white;
    font-size: 20px;
  }

  .detail-content-modern {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .detail-label-modern {
    font-size: 12px;
    color: #6c757d;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
  }

  .detail-value-modern {
    font-size: 15px;
    color: #333;
    font-weight: 500;
  }

  .status-badge-modern {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .status-pending { background: #ffc107; color: #fff; }
  .status-approved { background: #28a745; color: #fff; }
  .status-rejected { background: #dc3545; color: #fff; }

  .form-section-modern {
    padding: 0 30px 30px;
    background: white;
  }

  .form-section-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .form-section-title i {
    color: #b30000;
    font-size: 20px;
  }

  .form-group-modern {
    margin-bottom: 20px;
  }

  .form-group-modern label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .form-group-modern .form-control,
  .form-group-modern textarea {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    transition: all 0.3s ease;
    font-family: 'Poppins', sans-serif;
  }

  .form-group-modern .form-control:focus,
  .form-group-modern textarea:focus {
    border-color: #b30000;
    box-shadow: 0 0 0 0.2rem rgba(179, 0, 0, 0.15);
  }

  .form-group-modern .form-control:disabled,
  .form-group-modern textarea:disabled {
    background: #f8f9fa;
    cursor: not-allowed;
  }

  #showStatusRequestModal .modal-footer {
    background: white;
    border-top: 2px solid #f0f0f0;
    padding: 20px 30px;
    border-radius: 0 0 20px 20px;
  }

  #showStatusRequestModal .modal-footer .btn {
    border-radius: 10px;
    padding: 12px 28px;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
  }

  #showStatusRequestModal .modal-footer .btn-primary {
    background: linear-gradient(135deg, #b30000 0%, #8b0000 100%);
    box-shadow: 0 4px 12px rgba(179, 0, 0, 0.3);
  }

  #showStatusRequestModal .modal-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(179, 0, 0, 0.4);
  }

  #showStatusRequestModal .modal-footer .btn-secondary {
    background: #6c757d;
    color: white;
  }

  #showStatusRequestModal .modal-footer .btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
  }

  /* Responsive styles */
  @media (max-width: 767px) {
    #showStatusRequestModal .modal-dialog {
      margin: 10px;
    }

    #showStatusRequestModal .modal-header {
      padding: 20px 16px;
    }

    .modal-title {
      font-size: 18px;
    }

    .profile-header-modern {
      padding: 30px 20px;
    }

    .profile-image-modern {
      width: 100px;
      height: 100px;
      border: 4px solid white;
    }

    .profile-name-modern {
      font-size: 22px;
    }

    .profile-details-modern,
    .form-section-modern {
      padding: 20px;
    }

    .detail-item-modern {
      padding: 12px 14px;
      margin-bottom: 10px;
    }

    .detail-icon-modern {
      width: 40px;
      height: 40px;
      margin-right: 12px;
    }

    .detail-icon-modern i {
      font-size: 18px;
    }

    .detail-value-modern {
      font-size: 13px;
    }

    #showStatusRequestModal .modal-footer {
      padding: 16px 20px;
      flex-direction: column;
    }

    #showStatusRequestModal .modal-footer .btn {
      width: 100%;
      margin: 4px 0 !important;
    }
  }

  @media (max-width: 480px) {
    .profile-header-modern {
      padding: 24px 16px;
    }

    .profile-image-modern {
      width: 90px;
      height: 90px;
    }

    .profile-name-modern {
      font-size: 20px;
    }

    .detail-item-modern {
      flex-direction: column;
      align-items: flex-start;
    }

    .detail-content-modern {
      width: 100%;
      flex-direction: column;
      align-items: flex-start;
      margin-top: 8px;
    }

    .status-badge-modern {
      margin-top: 8px;
      align-self: flex-start;
    }
  }

  /* Scrollbar styling */
  #showStatusRequestModal .modal-body::-webkit-scrollbar {
    width: 6px;
  }

  #showStatusRequestModal .modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
  }

  #showStatusRequestModal .modal-body::-webkit-scrollbar-thumb {
    background: #b30000;
    border-radius: 10px;
  }

  #showStatusRequestModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: #8b0000;
  }
</style>

<div class="modal fade" id="showStatusRequestModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <form id="editRequestForm" method="post">

        <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-user-circle"></i> Resident Profile</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
      <div class="modal-body">
        <div class="container-fluid">

                <input type="hidden" value="<?= $residence_id ?>" name="residence_id" id="residence_id">
                <input type="hidden" value="<?= $certificate_id ?>" name="certificate_id" id="certificate_id">
    
            <!-- Modern Profile Card -->
            <div class="profile-card-modern">
              <!-- Profile Header -->
              <div class="profile-header-modern">
                <?php 
                if($row_request_status['image'] != '' || $row_request_status['image'] != null){
                  echo '<img class="profile-image-modern" src="'.$row_request_status['image_path'].'" alt="User Avatar">';
                }else{
                  echo '<img class="profile-image-modern" src="../assets/dist/img/blank_image.png" alt="User Avatar">';
                }
                ?>
                <h3 class="profile-name-modern"><?= $row_request_status['first_name'].' '.$row_request_status['last_name'] ?></h3>
              </div>

              <!-- Profile Details -->
              <div class="profile-details-modern">
                <!-- Resident ID -->
                <div class="detail-item-modern">
                  <div class="detail-icon-modern">
                    <i class="fas fa-id-card"></i>
                  </div>
                  <div class="detail-content-modern">
                    <div>
                      <div class="detail-label-modern">Resident ID</div>
                      <div class="detail-value-modern"><?= $row_request_status['residence_id'] ?></div>
                    </div>
                  </div>
                </div>

                <!-- Address -->
                <div class="detail-item-modern">
                  <div class="detail-icon-modern">
                    <i class="fas fa-map-marker-alt"></i>
                  </div>
                  <div class="detail-content-modern">
                    <div>
                      <div class="detail-label-modern">Address</div>
                      <div class="detail-value-modern"><?= $row_request_status['address'] ?></div>
                    </div>
                  </div>
                </div>

                <!-- Gender & Age -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="detail-item-modern">
                      <div class="detail-icon-modern">
                        <i class="fas fa-venus-mars"></i>
                      </div>
                      <div class="detail-content-modern">
                        <div>
                          <div class="detail-label-modern">Gender</div>
                          <div class="detail-value-modern"><?= $row_request_status['gender'] ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="detail-item-modern">
                      <div class="detail-icon-modern">
                        <i class="fas fa-birthday-cake"></i>
                      </div>
                      <div class="detail-content-modern">
                        <div>
                          <div class="detail-label-modern">Age</div>
                          <div class="detail-value-modern"><?= $row_request_status['age'] ?> years old</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Contact Number -->
                <div class="detail-item-modern">
                  <div class="detail-icon-modern">
                    <i class="fas fa-phone"></i>
                  </div>
                  <div class="detail-content-modern">
                    <div>
                      <div class="detail-label-modern">Contact Number</div>
                      <div class="detail-value-modern"><?= $row_request_status['contact_number'] ?></div>
                    </div>
                  </div>
                </div>

                <!-- Status -->
                <div class="detail-item-modern">
                  <div class="detail-icon-modern">
                    <i class="fas fa-info-circle"></i>
                  </div>
                  <div class="detail-content-modern">
                    <div>
                      <div class="detail-label-modern">Request Status</div>
                    </div>
                    <div>
                      <?php 
                      if($row_request_status['status'] == 'REJECTED'){
                          echo '<span class="status-badge-modern status-rejected">'.$row_request_status['status'].'</span>';
                        }elseif($row_request_status['status'] == 'PENDING') {
                          echo '<span class="status-badge-modern status-pending">'.$row_request_status['status'].'</span>';
                        }else{
                          echo '<span class="status-badge-modern status-approved">'.$row_request_status['status'].'</span>';
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Section -->
            <div class="form-section-modern">
              <div class="form-section-title">
                <i class="fas fa-file-alt"></i>
                <span>Certificate Details</span>
              </div>

              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group-modern">
                    <label>Purpose</label>
                    <input type="text" name="purpose" id="edit_purpose" class="form-control" value="<?= $row_request_status['purpose'] ?>" <?= $row_request_status['status'] != 'PENDING'? 'disabled': '' ?>>
                  </div>
                </div>
               
                <?php 
                if($row_request_status['status'] != 'PENDING'){
                  echo '
                  <style>
                    #showStatusRequestModal .modal-body{
                      max-height: 75vh;
                      overflow-y: auto;
                    }
                  </style>
                  <div class="col-sm-12">
                    <div class="form-group-modern">
                      <label>Admin Message</label>
                      <textarea name="message" id="message" class="form-control" cols="5" rows="3" disabled>'.$row_request_status['message'].'</textarea>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group-modern">
                      <label>Date Issued</label>
                      <input type="date" name="date_issued" id="date_issued" class="form-control" value="'.$row_request_status['date_issued'].'" disabled>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group-modern">
                      <label>Date Expired</label>
                      <input type="date" name="date_expired" id="date_expired" class="form-control" value="'.$row_request_status['date_expired'].'" disabled>
                    </div>
                  </div>';
                }
                ?>
              </div>
            </div>

        </div>
      </div>
      <div class="modal-footer">
      <?= $row_request_status['status'] == 'PENDING' ?  '<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Request</button>': '';  ?>
      <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
      </div>

      </form>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){






    $("#editRequestForm").submit(function(e){
        e.preventDefault();
        
        
        var edit_purpose = $("#edit_purpose").val();
        
        if(edit_purpose == ''){
            
                     Swal.fire({
                      title: '<strong class="text-danger">ERROR</strong>',
                      type: 'error',
                      html: '<b>PURPOSE IS REQUIRED<b>',
                      width: '400px',
                      confirmButtonColor: '#6610f2',
                    })
            
        }else{
             Swal.fire({
                title: '<strong class="text-info">ARE YOU SURE?</strong>',
                html: "You want Edit this Request?",
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                allowOutsideClick: false,
                confirmButtonText: 'Yes, Edit it!',
                width: '400px',
              }).then((result) => {
                if (result.value) {
                  $.ajax({
                    url: 'requestStatus.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    cache: false,
                    success:function(data){
                      Swal.fire({
                        title: '<strong class="text-success">Success</strong>',
                        type: 'success',
                        html: '<b>Edited Request has Successfully<b>',
                        width: '400px',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        timer: 2000
                      }).then(()=>{
                        $("#tableRequest").DataTable().ajax.reload();
                        $("#showStatusRequestModal").modal('hide');
                      })
                    }
                  }).fail(function(){
                    Swal.fire({
                      title: '<strong class="text-danger">Ooppss..</strong>',
                      type: 'error',
                      html: '<b>Something went wrong with ajax !<b>',
                      width: '400px',
                      confirmButtonColor: '#6610f2',
                    })
                  })
                }
              })
            
        }

           

    })
    $('[data-toggle="tooltip"]').tooltip();


  })

</script>
<script>
// Restricts input for each element in the set of matched elements to the given inputFilter.
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));



  $("#edit_purpose").inputFilter(function(value) {
  return /^[a-z, ]*$/i.test(value); 
  });
  


</script>