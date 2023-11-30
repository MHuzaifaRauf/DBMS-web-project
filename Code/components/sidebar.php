<style>
    .sidebar {
        transition: all .3s;
        width: 200px;
        background-color:  #E0E8F0;

        height: calc(100vh - 80px);
        /* Subtract navbar height from the sidebar height */
        position: fixed;
        top: 90px;
        /* Set the top position to the height of the navbar */
        left: 0;
        overflow-y: auto;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);

    }

    .sidebar ul {
        padding: 0;
        margin: 0;
        list-style-type: none;
    }

    .sidebar li {

        padding: 10px;
        border-bottom: 1px solid #ccc;
    }

    .sidebar li:last-child {
        border-bottom: none;
    }

    .sidebar li a {
        color: #AE445A;

        text-decoration: none;
        font-size: large;
    }

    .sidebar .selected a,
    .sidebar .selected i{
        color: black !important;
    }

    .sidebar li i {
        color: rgba(0, 0, 0, 0.7);

        margin-right: 10px;

    }

    .content {
        margin-left: 200px;
        /* Adjust this value to match the sidebar width */
        padding: 20px;
    }

    /* Media Query for responsive design */
    @media (max-width: 768px) {
        .sidebar {
            display: none;
            width: 100%;
            height: auto;
            position: relative;
        }

        .content {
            margin-left: 0;
        }
    }
</style>
<?php
require_once "utils/data.php";
 
?>
<div class="sidebar">
    <ul style="margin-top:20px">
        <?php

        foreach ($tables as $title): ?>
            <li
                class="<?php echo stripos(strtolower($_SERVER['REQUEST_URI']), strtolower($title['tag'])) !== false ? 'selected' : ''; ?>">


                <i class="<?php echo $title['icon'] ?>">
                </i><a href="<?php echo $title['link'] ?>">
                    <?php echo $title['name']; ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>