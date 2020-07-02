<!-- DATA TABLES -->
<aside class="right-side"> 
    <div class="bc-area">          
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url("admin/dashboard"); ?>">Dashboard</a></li>
            <li class="active">Ads</li>
        </ul>
        <div class="clearfix"></div>
    </div>  
    <div id="edit-area">
        <div class="span9" id="article">      <h3 class="box-title">Users  Lists</h3></div>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <!--                        <h3 class="box-title"></h3> -->
                        </div><!-- /.box-header -->
                        <div class="box-body table-responsive">
                                <div id="notifier" ></div>
                            <table id="example1" class="table table-bordered table-striped">
                                  <?php if ($records->num_rows() > 0) { ?>
                                <thead>
                                    <tr>
                                            <th width="5%">ID</th>
                                            <th width="19%">Name</th>
                                            <!--<th width="19%">Email</th>-->
                                            <th width="19%">User Profile</th>
                                            <!--<th width="19%">Total Bonus Points</th>-->
                                            <th width="19%">Status</th>

                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sno = 0;
                                    foreach ($records->result() as $row) {
                                        ?>
                                        <tr>
                                            <td><?php
                                                $sno++;
                                                echo $sno;
                                                ?></td>
                                            <td><?php echo $row->name;  ?></td>
                                             <!--<td><?php echo $row->email; ?></td>-->
                                             <td><a href="#" data-toggle="modal" data-target="#myModal_<?php echo $row->id ?>">Check User Profile</a></td>
                                <div class="modal fade" id="myModal_<?php echo $row->id ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                        <h4 class="modal-title" id="myModalLabel">User Profile</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <label> Name : </label>
                                                       <?php echo $row->name;  ?>
                                                    </div>
                                                    <div class="modal-body">
                                                           <label>Email : </label>
                                                       <?php echo $row->email;  ?>
                                                    </div>
                                                    <div class="modal-body">
                                                          <label>Contact Number : </label>
                                                       <?php echo $row->contact_no;  ?>
                                                    </div>
                                                       
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                            
                                                 <td>
                                                            <input class="statusinput" type="radio" name="post_status_<?php echo $row->uid ?>" id="A_<?php echo $row->uid ?>" value="A" <?php echo ($row->status == "A") ? "checked" : ""; ?> /> <label style="display: inline;" for="A_<?php echo $row->uid; ?>">Active</label>
                                                            <br />
                                                            <input class="statusinput" type="radio" name="post_status_<?php echo $row->uid ?>" id="NA_<?php echo $row->uid ?>" value="NA" <?php echo ($row->status == "NA") ? "checked" : ""; ?> /> <label style="display: inline;" for="NA_<?php echo $row->uid; ?>">Pending</label>
                                                            <br />
                                                            <input class="statusinput" type="radio" name="post_status_<?php echo $row->uid ?>" id="B_<?php echo $row->uid ?>" value="B" <?php echo ($row->status == "B") ? "checked" : ""; ?> /> <label style="display: inline;" for="B_<?php echo $row->uid; ?>">Blocked</label>
                                                        </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                  <?php } else {?>
                                <tr><td>No Users To Display</td></tr>
                                  <?php }?>
                            </table>  

                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </div>
            </div>

        </section>
        

        <?php if ($this->pagination->create_links() != '') { ?>
            <div>
             <ul class="pagination " style="margin-left:40%;"><?php echo $this->pagination->create_links(); ?></ul>
            </div>
        <?php } ?>
        

   
        <div class="clearfix"></div>
    </div>
</aside>    
<script type="text/javascript">
$(document).ready(function () {
    $(".statusinput").click(function () {
        var conf_res = confirm("Are you sure to change the status?");
        if (conf_res) {
            var inp_id = $(this).attr("id");
            var words = inp_id.split("_");
            var status = words[0];
            var id = words[1];
            $.ajax({
            type: "POST",
             url: "<?php echo site_url("admin/users/change_status");?>",
            data: {"rid": id, "status": status},
            cache: false,
            success: function(data) {
               $("#notifier").html(data).addClass('alert alert-success');
//                 setTimeout(function() { window.location = "<?php echo site_url('admin/dashboard');?>"; }, 2000);
            }
        });
        }
    })
});
</script>


