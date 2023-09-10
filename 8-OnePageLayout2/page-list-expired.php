<?php
/**
 * Template name: Page - List Users Detail
 *
 * @package          Flatsome\Templates
 * @flatsome-version 3.16.0
 */

$current_role = get_current_user_role();
$current_user_id = get_current_user_id();
$error_message = "";

$user_idd = $_GET['id'];

$user = get_user_by('ID', $user_idd);
$user_mobile = get_user_meta($user_idd, 'mobile', true);
$first_name = get_user_meta($user_idd, 'first_name', true);
$created_by = get_user_meta($user_idd, 'created_by', true);
$created_by = get_user_meta($created_by, 'first_name', true);
$user_Role = $user->roles;
$username = $user->user_login;
$user_email = $user->user_email;

get_header();

?>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<div class="container">


    <?php
    
    /*Find the Post_ID*/
    if($user_Role[0] == "dealer"){
    $posts = get_posts(
        array(
            'meta_key' => $user_Role[0],
            'meta_value' => $user_idd,
            'post_type' => 'post',
            'posts_per_page' => -1,
            'fields' => 'ids',
            // Only retrieve post IDs
        )
    );
    }else if($user_Role[0] == "Editor"){
       $posts = get_posts(
        array(
        'post_type' => 'post',
        'posts_per_page' => -1,
        'fields' => 'ids', // 仅检索帖子 ID
        'meta_query' => array(
            'relation' => 'AND', // 使用 AND 连接两个条件
            array(
                'key' => 'distributor',
                'value' => '',
            ),
            // 在这里添加第二个条件
            array(
                'key' => 'dealer', // 用实际的meta_key替换这里的值
                'value' => '', // 用实际的meta_value替换这里的值
            ),
        )
        
    )
);
   
    }
    
    
    
    $wanted_user = "customer";
    // If there are matching posts, get the list of users from them
    if (!empty($posts)) {
        $user_list = array();
        foreach ($posts as $post_id) {
            
            $users = get_field($wanted_user, $post_id);
            
            if (!empty($users)) {
                // Ensure that $users is an array of user IDs
                if (is_array($users)) {
                    foreach ($users as $user_id) {
                        array_push($user_list, $user_id);
                    }
                } else {
                    array_push($user_list, $users);
                }
            } else {
                    $error_message = "No ".$wanted_user." apply to"."$first_name";
            }
        }
        // Get all the users
        $argment = array(
            'include' => $user_list
        );
        $user_ids = get_users($argment);
       
    } else {
        $error_message = "No user founds";
    }
    //} else {
//   $error_message .= "Select the user type that you want.";
//}
    get_header();
    ?>
    <?php do_action('flatsome_before_page'); ?>

    <div id="content" role="main" class="content-area">

        <div class="row">
            <?php the_content(); ?>

            <h2>User Detail</h2>
            <br>
            <br>
            <br>
            <div>Name</div>
            <h2>
                <?php echo $first_name; ?>
            </h2>

            <div>Username</div>
            <h4>
                <?php echo $username; ?>
            </h4>

            <div>Mobile Number</div>
            <h4>
                <?php echo $user_mobile; ?>
            </h4>

            <div>Created by</div>
            <h4>
                <?php echo $created_by ?>
            </h4>

            <div>Role</div>
            <h4>
                <?php echo $user_Role[0]; ?>
            </h4>

            <br>
            <br>
            <br>



        </div><!--end of row-->
        
        
        
        <div class="row">
            <div class="tab">
              <button class="tablinks active" onclick="openCity(event, 'Customers')">Customers <b>(<?php echo count($user_ids) ?>)</b></button>
              <!--<button class="tablinks" onclick="openCity(event, 'Paris')">Dealers</button>
              <button class="tablinks" onclick="openCity(event, 'Tokyo')">Product</button>-->
            </div>
        </div>
        
        <div class="row tabcontent " id="Customers" style="display: block;">
            
            <?php
            if (empty($error_message)) {
                ?>

                <div class="table-container">

                    <h2>
                        <? echo $first_name . "'s " . ucwords(strtolower($wanted_user)); ?>
                    </h2>
                    <div class="search-container">
                        <input type="text" id="search-input" placeholder="Search..." />
                    </div>
                    <table class="responsive-table">
                        <tr>
                            <th>Name</th>
                            <th>Mobile No.</th>
                            <th>Email</th>
                            <th>Registration Date</th>
                            <th>Product</th>
                            <?php if ($user_Role[0] == "distributor" && $wanted_user != "customer") { ?>
                                <th>Customers</th>
                            <?php } ?>
                        </tr>
                        
                        
                        <?php foreach ($user_ids as $user) {
                            $current_user_index++;
                            $suggestions[] = $user->display_name; // Increment the current user index?>

                            <tr class="user-row">
                                <td><!--name-->
                                    <?php echo $user->display_name ?>
                                </td>
                                
                                <td><!--Mobile No-->
                                    <?php echo get_user_meta($user->ID, 'mobile', true); ?>
                                </td>
                                
                                <td><!--Email-->
                                    <?php echo $user->user_email ?>
                                </td>
                                <td><!--user_registered date time-->
                                    <?php echo $user->user_registered ?>
                                </td>
                                
                                <td><!--Product-->

                                    <?php
                                    /*找回直接的顾客*/
                                    if ($user_Role[0] == "distributor") {
                                        $Userproduct_query = array(
                                            'post_type' => 'post',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                // Combine conditions with an "AND" relationship
                                                array(
                                                    'key' => "customer",
                                                    'value' => $user->ID,
                                                    'compare' => '=',
                                                    // Match exact value
                                                ),
                                                array(
                                                    'key' => 'dealer',
                                                    'value' => '',
                                                    'compare' => '=',
                                                    // Match exact value
                                                ),
                                            ),
                                        );
                                    } else if ($user_Role[0] == "dealer") {
                                        $Userproduct_query = array(
                                            'post_type' => 'post',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                // Combine conditions with an "AND" relationship
                                                array(
                                                    'key' => "customer",
                                                    'value' => $user->ID,
                                                    'compare' => '=',
                                                    // Match exact value
                                                ),
                                                array(
                                                    'key' => 'dealer',
                                                    'value' => $user_idd,
                                                    'compare' => '=',
                                                    // Match exact value
                                                ),
                                            ),
                                        );

                                    } else if ($user_Role[0] == "editor") {
                                        $Userproduct_query = array(
                                            'post_type' => 'post',
                                            'post_status' => 'publish',
                                            'posts_per_page' => -1,
                                            'meta_query' => array(
                                                'relation' => 'AND',
                                                // Combine conditions with an "AND" relationship
                                               
                                                array(
                                                    'key' => 'distributor',
                                                    'value' => '',
                                                    'compare' => '=',
                                                    // Match exact value
                                                ),
                                                array(
                                                    'key' => 'dealer',
                                                    'value' => '',
                                                    'compare' => '=',
                                                    // Match exact value
                                                ),
                                            ),
                                        );

                                    }
                                    
                                    $query = new WP_Query($Userproduct_query);

                                    while ($query->have_posts()): $query->the_post(); ?>
                                        <b><a href='<?php echo the_permalink(); ?>'><?php echo the_title(); ?></a></b><br>
                                    <?php endwhile; // end of the loop. ?>
                                    
                                </td>
                                <?php if ($user_Role[0] == "distributor" && $wanted_user != "customer") {
                                    echo "<td>";
                                    $ps = get_posts(
                                        array(
                                            'meta_key' => 'dealer',
                                            'meta_value' => $user->ID,
                                            'post_type' => 'post',
                                            'posts_per_page' => -1,
                                            'fields' => 'ids',
                                            // Only retrieve post IDs
                                        )
                                    );
                                    // If there are matching posts, get the list of users from them
                                    if (!empty($ps)) {
                                        $customer_list = array();

                                        foreach ($ps as $pid) {
                                            $customers = get_field('customer', $pid);
                                            if (!empty($customers)) {
                                                // Ensure that $users is an array of user IDs
                                                if (is_array($customers)) {
                                                    foreach ($customers as $user_id) {
                                                        array_push($customer_list, $user_id);
                                                    }
                                                } else {
                                                    array_push($customer_list, $customers);
                                                }
                                            }
                                        }
                                        print_r($dealer);
                                        // Get all the users
                                        $argment = array(
                                            'include' => $customer_list
                                        );
                                        $customers = get_users($argment);
                                        foreach ($customers as $customer):
                                            echo $customer->display_name . "<br>";
                                        endforeach;
                                    }
                                    ?>
                                    <td></td>
                                <?php } ?>
                            </tr>

                        <?php } ?><!--end of foreach -->
                    </table><!--end of table -->
                </div><!--end of table-container-->

                <div class="pagination">
                    <?php for ($i = 1; $i <= ceil(count($user_ids) / 10); $i++): ?>
                        <span class="page-link">
                            <?php if (count($user_ids) > 10)
                                echo $i; ?>
                        </span>
                    <?php endfor; ?>
                </div><!--end of pagination-->

                <!--end of table-container-->
                <?php
            } else
                echo $error_message;
            ?>

        </div><!--end of row-->
    </div><!--end of content-->
</div><!--end of container-->

<?php do_action('flatsome_after_page'); ?>
<?php get_footer(); ?>

<script>
    var originalUserRows = []; // 在 $(document).ready() 之外定义一个空数组
    var suggestions = <?php echo json_encode($suggestions); ?>;

    $(document).ready(function () {
        originalUserRows = $(".user-row").toArray(); // 将初始的用户行转换为数组保存

        $(".user-row").slice(0, 10).addClass("active"); // 初始显示前10行

        $(".pagination span:first").addClass("underline"); // 初始下划线

        $(".pagination span").click(function () {
            // 移除所有页码的下划线，然后为点击的页码添加下划线
            $(".pagination span").removeClass("underline");
            $(this).addClass("underline");

            var pageNum = parseInt($(this).text());
            var startIndex = (pageNum - 1) * 10;
            var endIndex = startIndex + 10;

            $(".user-row").removeClass("active");
            $(".user-row").slice(startIndex, endIndex).addClass("active");
        });

        $("#search-input").autocomplete({
            source: suggestions,
            select: function (event, ui) {
                var selectedValue = ui.item.value;
                $("#search-input").val(selectedValue);
                performSearch(selectedValue);
                return false;
            }
        });

        $("#search-input").on("input", function () {
            var query = $(this).val().toLowerCase();

            if (query === "") {
                // 如果搜索框为空，恢复默认显示前10行
                $(".user-row").removeClass("active");
                $(originalUserRows).slice(0, 10).addClass("active");
                return;
            }

            $(".user-row").each(function () {
                var userText = $(this).text().toLowerCase();
                if (userText.includes(query.toLowerCase())) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        function performSearch(query) {
            $(".user-row").each(function () {
                var userText = $(this).text().toLowerCase();
                if (userText.includes(query.toLowerCase())) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
    
    
    
    /*tab controller*/
    function openCity(evt, cityName) {
  // Declare all variables
  var i, tabcontent, tablinks;

  // Get all elements with class="tabcontent" and hide them
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  // Get all elements with class="tablinks" and remove the class "active"
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  // Show the current tab, and add an "active" class to the button that opened the tab
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}
    

</script>

<style>
    .ui-menu-item {
        background-color: white;
        cursor: pointer;
        border: 1px solid #dfdfdf;
        margin: 0;
        width: 300px;
        padding: 1vh;
        list-style-type: none;
    }

    .pagination span.underline {
        font-weight: 900;
        color: #3b96bb;
        text-align: center;
        font-size: 1.2em;
    }

    .user-row {
        display: none;
    }

    .user-row.active {
        display: table-row;

    }

    .pagination {
        margin-top: 10px;
    }

    .page-link {
        cursor: pointer;
        margin-right: 5px;
    }
    
    /*tab*/
    /* Style the tab */
.tab {
      overflow: hidden;
    border-bottom: 3px solid #006ea8;
}

/* Style the buttons that are used to open the tab content */
.tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  margin-bottom :0;
}

/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #ddd;
}

/* Create an active/current tablink class */
.tab button.active {
  background-color: #006ea8;
    color: white;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border-top: none;
}
    
</style>