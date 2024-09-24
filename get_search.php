<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {
    if (isset($_GET['ISBNNumber'])) {
        $id = $_GET['ISBNNumber'];
        $status = 1;
        $sql = "update tblbooks set isIssued=:status  WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_STR);
        $query->bindParam(':isIssued', $status, PDO::PARAM_STR);
        $query->execute();
        header('location:listed-books.php');
    }


?>

    
    <!DOCTYPE HTML>

<html>
    <body>
   <div class="panel-body">


                                    <?php 
                                    //include('listed-books.php');  
                                    $search = $_POST['search'];  
                                    
                                      
                                    $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblauthors.AuthorName,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid,tblbooks.bookImage,tblbooks.isIssued from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId join tblauthors on tblauthors.id=tblbooks.AuthorId where tblbooks.BookName LIKE '$search%' OR tblcategory.CategoryName LIKE '$search%' OR tblauthors.AuthorName LIKE '$search%'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                                    $cnt = 1;
                                    if ($query->rowCount() > 0) {
                                        foreach ($results as $result) {               ?>
                                            <div class="col-md-4" style="float:left; height:300px;">




                                                <img src="admin/bookimg/<?php echo htmlentities($result->bookImage); ?>" width="100">
                                                <br /><b><?php echo htmlentities($result->BookName); ?></b><br />
                                                <?php echo htmlentities($result->CategoryName); ?><br />
                                                <?php echo htmlentities($result->AuthorName); ?><br />
                                                <?php echo htmlentities($result->ISBNNumber); ?><br />
                                                <?php if ($result->isIssued == '1') {?>
                                                    <p style="color:red;">Book Already issued</p>
                                                <?php }else { ?>                                                   

                                                                    <a href="listed-books.php?ISBNNumber=<?php echo htmlentities($result->ISBNNumber); ?>" onclick="return confirm('Are you sure you want to reserve this book?');"><button class="btn btn-primary"> Reserve</button>
                                                                    

                                                <?php } ?>
                                            </div>

                                    <?php $cnt = $cnt + 1;
                                        }
                                    } ?>


                                </div>
                                </div>
                            <!--End Advanced Tables -->
                        </div>
                    </div>



                </div>
            </div>
        </div>
        </body>

</html><?php } ?>