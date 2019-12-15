<aside class="right-side">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Site Settings
                        <!--<small>Content Managment System</small>-->
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i>Home</a></li>
                        <li class="active">Settings</li>
                    </ol>
                </section>

                

                <!-- Main content -->
                <section class="content invoice">                    
                    <!-- title row -->
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                 <img  src="<?php echo $site_logo;?>"  style=" height: 78px !important;width: 105px !important;"/><?php echo $records->row()->site_title; ?>
                            </h2>                            
                        </div><!-- /.col -->
                    </div>
                    <!-- info row -->
                    

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-xs-12 table-responsive">
                            <table class="table table-striped">
                               
                                <tbody>
                                    <tr>
                                      <td><strong>Address</strong></td>  <td><?php echo $records->row()->address; ?></td></tr>
                                    <tr> <td><strong>Contact Number</strong></td><td><?php echo $records->row()->contact_no1 ?></td></tr>
                                 <?php if($records->row()->contact_no2) {?>   <tr><td><strong>Alternate Contact Number</strong></td> <td><?php echo $records->row()->contact_no2 ?></td></tr><?php } ?>
                                    <tr>  <td><strong>Contact Email</strong></td><td> <?php echo $records->row()->contact_email ?></td></tr>
                                  <?php if($records->row()->news) {?>    <tr>  <td><strong>News</strong></td>  <td><?php echo $records->row()->news ?></td></tr><?php } ?>
                                    <tr>  <td><strong>Home Page Video</strong></td><td> <?php echo $records->row()->homepage_video ?></td></tr>
                                </tbody>
                            </table>                            
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-xs-12 text-center">
                          <a href="<?php echo site_url('admin/settings/edit');?>">  <button class="btn btn-success edit"><i class="fa fa-edit"></i> Edit Details </button>  </a>
                        </div>
                    </div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
            
