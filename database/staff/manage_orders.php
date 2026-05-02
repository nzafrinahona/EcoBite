<tr>

    <td>
        <?php echo $row['order_id']; ?>
    </td>

    <td>
        <?php echo $row['customer_name']; ?>
    </td>

    <td>

        <?php

        $status = $row['order_status'];

        if($status == "Completed") {

            echo "<span style='color:green; font-weight:bold;'>Completed</span>";

        }
        elseif($status == "No-show") {

            echo "<span style='color:red; font-weight:bold;'>No-show</span>";

        }
        else {

            echo "<span style='color:orange; font-weight:bold;'>Pending</span>";

        }

        ?>

    </td>

    <td>

        <a href="update_order_status.php?order_id=<?php echo $row['order_id']; ?>&status=Completed">

            <button>
                Mark Completed
            </button>

        </a>

        <a href="update_order_status.php?order_id=<?php echo $row['order_id']; ?>&status=No-show">

            <button>
                Mark No-show
            </button>

        </a>

    </td>

</tr>