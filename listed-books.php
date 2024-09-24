<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
    header('location:index.php');
} else {

    if (isset($_GET['id'])) {
        $studentid = $_SESSION['stdid'];
        $bookid = $_GET['id'];
        $isissued = 1;
        try {
            $sql = "INSERT INTO tblreserved (StudentID, BookId, ReservedStatus) VALUES (:studentid, :bookid, :isissued)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
            $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
            $query->bindParam(':isissued', $isissued, PDO::PARAM_INT);
            $query->execute();
            $lastInsertId = $dbh->lastInsertId();
            if ($lastInsertId) {
                $_SESSION['msg'] = "Book reserved successfully";
                header('location:listed-books.php');
                exit; // Added exit to stop further execution
            } else {
                $_SESSION['error'] = "Something went wrong. Please try again";
                header('location:listed-books.php');
                exit; // Added exit to stop further execution
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            header('location:listed-books.php');
            exit; // Added exit to stop further execution
        }
    }
    



    // if (isset($_GET['id'])) {
    //     $id = $_GET['id'];
    //     $status = 1;
    //     $sql = "update tblbooks set isIssued= :status  WHERE ISBNNumber= :id";
    //     $query = $dbh->prepare($sql);
    //     $query->bindParam(':id', $id, PDO::PARAM_STR);
    //     $query->bindParam(':status', $status, PDO::PARAM_INT);
    //     $query->execute();
    //     header('location:listed-books.php');
    // }


?>
    <!DOCTYPE html>
    <html xmlns="">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Online Library Management System | Issued Books</title>
        <!-- BOOTSTRAP CORE STYLE  -->
        <link href="assets/css/bootstrap.css" rel="stylesheet" />
        <!-- FONT AWESOME STYLE  -->
        <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- DATATABLE STYLE  -->
        <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
        <!-- CUSTOM STYLE  -->
        <link href="assets/css/style.css" rel="stylesheet" />
        <!-- GOOGLE FONT -->
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />

    



        <script>
            // function for get student name
            function getsearch() {
                $("#loaderIcon").show();
                jQuery.ajax({
                    url: "get_search.php",
                    data: 'search=' + $("#search").val(),
                    type: "POST",
                    success: function(data) {
                        $("#get_search").html(data);
                        $("#loaderIcon").hide();
                    },
                    error: function() {}
                });
            }
        </script>


    </head>

    <body>
        <!------MENU SECTION START-->
        <?php include('includes/header.php'); ?>
        <!-- MENU SECTION END-->
        <div class="content-wrapper">
            <div class="container">
                <div class="row pad-botm">
                    <div class="col-md-12">
                        <h4 class="header-line">Manage Issued Books</h4>
                    </div>
                    <div class="search-container">
                        <form role="form" method="post">
                            <input type="text" placeholder="Search Book/Author/Category" name="search" id="search" oninput="getsearch()">
                            <!-- <button type="button" id="searchButton" onclick="getsearch()"><i class="fa fa-search"></i></button> -->
                        </form>
                    </div>


                    <div class="row" id="get_search">
                        <div class="col-md-12">
                            <!-- Advanced Tables -->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Issued Books
                                </div>
                                <div class="panel-body">


                                    <?php $sql = "SELECT tblbooks.BookName,tblcategory.CategoryName,tblauthors.AuthorName,tblbooks.ISBNNumber,tblbooks.BookPrice,tblbooks.id as bookid,tblbooks.bookImage,tblbooks.isIssued from  tblbooks join tblcategory on tblcategory.id=tblbooks.CatId join tblauthors on tblauthors.id=tblbooks.AuthorId";
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
                                                <?php echo 'ISBN Number: '; ?>
                                                <?php echo htmlentities($result->ISBNNumber); ?><br />
                                                <?php if ($result->isIssued == '1') {?> 
                                                    
                                                    <p style="color:red;">Book Reserved</p><?php
                                                 } else { ?>
                                                    
                                                    <!-- echo htmlentities("Reserve"); -->
                                                    
                                                    <a href="listed-books.php?id=<?php echo htmlentities($result->ISBNNumber); ?>" onclick="return confirm('Are you sure you want to reserve this book?');"><button class="btn btn-primary"> Reserve</button></a>

                                                    
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

        <!-- CONTENT-WRAPPER SECTION END-->
        <?php include('includes/footer.php'); ?>
        <!-- FOOTER SECTION END-->
        <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
        <!-- CORE JQUERY  -->
        <script src="assets/js/jquery-1.10.2.js"></script>
        <!-- BOOTSTRAP SCRIPTS  -->
        <script src="assets/js/bootstrap.js"></script>
        <!-- DATATABLE SCRIPTS  -->
        <script src="assets/js/dataTables/jquery.dataTables.js"></script>
        <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
        <!-- CUSTOM SCRIPTS  -->
        <script src="assets/js/custom.js"></script>

    </body>

    </html>
<?php } ?>