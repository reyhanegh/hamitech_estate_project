<?
require_once "functions/database.php";
$action = new Action();
?>

<!-- ----------- start sidebar ------------------------------------------------------------------------------------- -->
<ul id="sidebarnav">

    <li class="nav-label">| پنل</li>

    <li>
        <a class="has-arrow" href="panel.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">داشبورد</span>
        </a>
    </li>
    <hr class="m-0">

    <li>
        <a class="has-arrow" href="post-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">املاک</span>
        </a>
    </li>
    <hr class="m-0">
    
    <li>
        <a class="has-arrow" href="post-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu"> ثبت درخواست مشتریان</span>
        </a>
    </li>
    
    <hr class="m-0">

    <li>
        <a class="has-arrow" href="post-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">گزارش املاک درخواستی  </span>
        </a>
    </li>

    <!-- <li>
        <a class="has-arrow" href="report_user_signup.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">گزارشات ثبت نام کاربران</span>
        </a>
    </li> -->

    <hr class="m-0">


    <li class="nav-label">| مدیریت</li>

    <? if ($action->auth()) { ?>

<li>
    <a class="has-arrow" href="admin-list.php" aria-expanded="false">
        <i class="fas fa-user-tie"></i>
        <span class="hide-menu">مدیران</span>
    </a>
</li>
<hr class="m-0">

<li>
        <a class="has-arrow" href="post-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">شهرهای تحت پوشش</span>
        </a>
    </li>
    <hr class="m-0">
    <li>
        <a class="has-arrow" href="post-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">دسته بندی املاک</span>
        </a>
    </li>
    <hr class="m-0">

<? } ?>

<hr class="m-0">

    <li>
        <a class="has-arrow" href="user-list.php" aria-expanded="false">
            <i class="fa fa-user"></i>
            <span class="hide-menu">مشتریان</span>
        </a>
    </li>

    <hr class="m-0">

    <li>
        <a class="has-arrow" href="category-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">پکیج های سایت</span>
        </a>
    </li>
    <hr class="m-0">

    <li>
        <a class="has-arrow" href="comment.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">آژانس های املاک</span>
        </a>
    </li>
    
    <hr class="m-0">

    <li>
        <a class="has-arrow" href="post-list.php" aria-expanded="false">
            <i class="fa fa-dashboard"></i>
            <span class="hide-menu">کاربران دارای پکیج</span>
        </a>
    </li>

   

</ul>
<!-- ----------- end sidebar --------------------------------------------------------------------------------------- -->
