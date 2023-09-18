<?php

use App\Models\Category;
use App\Models\Order;

$sum = 0;
$sumCategory = 0;
$products_sale = 0;
foreach ($successfulOrder as $order) {
  $sum += $order->total_price;
  foreach ($order->products() as $prd) {
    $products_sale += $prd->quantity;
  }
}
?>

<div class="w-full bg-neutral-100">
  <div class="w-full mx-auto my-0 overflow-x-hidden">
    <div class="box-border w-full min-h-screen px-10 mt-10 sm:px-5">
      <div class="flex justify-between">
        <h1 class="text-xl font-bold">
          Dashboard
        </h1>
      </div>
      <div>
        <form action="<?php echo BASE_URI . "/dashboard/filter"; ?>" method="POST">
          <label for="filter-date">Date:</label>
          <select id="filter-date" name="filter-date">
            <?php
            for ($day = 1; $day <= 31; $day++) {
              $dayValue = sprintf("%02d", $day); 
              echo "<option value=\"$dayValue\">$dayValue</option>";
            }
            ?>
          </select>

          <label for="filter-month">Month:</label>
          <select id="filter-month" name="filter-month">
            <?php
            for ($month = 1; $month <= 12; $month++) {
              $monthValue = sprintf("%02d", $month); 
              $monthName = date("F", mktime(0, 0, 0, $month, 1)); 
              echo "<option value=\"$monthValue\">$monthValue - $monthName</option>";
            }
            ?>
          </select>

          <label for="filter-year">Year:</label>
          <select id="filter-year" name="filter-year">
            <?php
            $currentYear = date("Y");

            for ($year = $currentYear; $year >= $currentYear - 10; $year--) {
              echo "<option value=\"$year\">$year</option>";
            }
            ?>
          </select>

          <button type="submit">Submit</button>
        </form>


      </div>
      <div class="box-border grid top-wrap 2xl:grid-cols-4 xl:gap-5 lg:grid-cols-2 lg:gap-2">
        <?php
        $id = ["total-revenue", "total-pending", "total-newUser", "total-newOrder"];
        $text = ["Sales", "Pending", "New Users", "New Orders"];
        $quantity = [$sum . " $", count($pendingOrders), $customer, $orders];
        $desc = [
          "We have sold " . $products_sale . " items",
          "Wait for accept",
          "More customer more money",
          "New things comin' up",
        ];
        $icon = [
          "fa-solid fa-arrow-trend-up",
          "fa-solid fa-dollar-sign",
          "fa-solid fa-user-group-crown",
          "fa-duotone fa-suitcase",
        ];
        $class = [
          "bg-blue-400 border-blue-400 shadow-[0_0_5px_1px_rgba(164,202,254,0.3)] shadow-blue-400",
          "bg-green-400 border-green-400 shadow-[0_0_5px_1px_rgba(49,196,141,0.3)] shadow-green-400",
          "bg-orange-400 border-orange-400 shadow-[0_0_5px_1px_rgba(255,138,76,0.3)] shadow-orange-400",
          "bg-red-400 border-red-400 shadow-[0_0_5px_1px_rgba(255,138,76,0.3)] shadow-red-400",
        ];
        for ($i = 1; $i <= 4; $i++) { ?>
          <div class="flex items-center justify-between w-full p-8 mt-5 bg-white shadow-lg rounded-2xl">
            <div class="flex flex-col gap-1 hero-one">
              <p class="text-sm font-semibold">
                <?php echo $text[$i - 1]; ?>
              </p>
              <p id="<?php echo $id[$i - 1] ?>" class="text-lg font-bold">
                <?php echo $quantity[$i - 1]; ?>
              </p>
              <p class="text-gray-500 break-words">
                <?php echo $desc[$i - 1]; ?>

              </p>
            </div>
            <div class="icon w-20 border-solid p-5 rounded-2xl text-center <?php echo $class[$i - 1]; ?>">
              <i class="<?php echo $icon[$i - 1]; ?> fa-xl text-white"></i>
            </div>
          </div>
        <?php }
        ?>
      </div>
      <div class="flex flex-wrap items-start justify-between w-full my-8 body-wrap">
        <div class="chart-layout xl:w-[65.5%] px-6 py-7 bg-white rounded-2xl shadow-lg sm:w-full h-full">
          <div class="flex items-center justify-between mb-3 top-content">
            <div class="total-revuenes">
              <p class="text-2xl font-semibold">Top 5 most selling products</p>
            </div>
            <div class="p-2 rounded-md chart-type hover:bg-slate-50">
              <label for="chart-type" class="font-medium text-black">Choose a type:</label>
              <select name="chart-ttype" id="c-type" class="px-4 py-1 rounded-md appearance-none focus:ring-2 focus:ring-primary-600 bg-gray-50" onchange="ChangeChart(this)">
                <option value="bar">Bar Chart</option>
                <option value="line">Line Chart</option>
                <option value="area">Area Chart</option>
              </select>
            </div>
          </div>
          <div id="chart">
          </div>
        </div>
        <div class="most-sold-items xl:w-[31.5%] py-4 px-4 bg-white rounded-2xl shadow-lg sm:w-full sm:mt-5 2xl:mt-0">
          <p class="mb-5 text-2xl font-bold">Most Sold By Category</p>
          <div class="flex flex-col gap-4">
            <?php
            $colors = ["red", "blue", "green", "yellow", "pink", "orange", "white", "gray", "brown"];
            ?>
            <?php foreach ($categorySold as $item) :
              $name = Category::findOne(["id" => $item["category_id"]]);
            ?>
              <div class="text-lg font-medium ">
                <?php echo $name->name ?>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div class="bg-<?php if (isset($colors[$name->id])) {
                                  echo $colors[$name->id];
                                } else {
                                  echo "teal";
                                } ?>-600 h-2.5 rounded-full" style="width: <?php echo (($item["num_orders"]) ? $item["num_orders"] : 0) * 10 ?>%"></div>
              </div>
            <?php endforeach ?>
          </div>
        </div>
      </div>
      <div class="my-8 overflow-hidden bg-white shadow-lg cursor-pointer table-statistics rounded-2xl">
        <div class="relative">
          <table class="w-full overflow-x-scroll text-sm text-center text-gray-500 table-auto rounded-2xl">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
              <tr>
                <?php
                $name = [
                  "Order ID",
                  "Customer Name",
                  "Date",
                  "Status",
                  "Amount",
                ];
                for ($i = 1; $i <= count($name); $i++) { ?>
                  <th scope="col" class="px-6 py-3">
                    <?php echo $name[$i - 1]; ?>
                  </th>
                <?php }
                ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $orders = Order::all();
              foreach (array_slice($orders, 0, 5) as $order) :
              ?>
                <tr class="transition-opacity bg-white border-b hover:bg-gray-200 even:bg-gray-100">
                  <td class="px-5 py-4 font-medium text-gray-900 whitespace-nowrap">
                    <?php echo $order->id ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $order->name ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php echo $order->create_at ?>
                  </td>
                  <td class="px-5 py-3 font-medium <?php echo ($order->status == 0) ? 'text-red-900' : 'text-primary-400' ?>">
                    <?php
                    if ($order->status == 0) {
                      echo "Pending";
                    } else {
                      echo "Successful";
                    }
                    ?>
                  </td>
                  <td class="px-5 py-3">
                    <?php
                    echo count($order->products());
                    ?>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  let test = <?php echo $test ?>;
  console.log(test);
  var series = [{
    name: 'Quantity',
    data: test.map(function(item) {
      return item.total_quantity;
    })
  }];
  var plotOptions = {
    bar: {
      horizontal: false,
      columnWidth: '55%',
      endingShape: 'rounded',
      distributed: true
    },
    line: {
      markers: {
        size: 6
      },
      distributed: true
    },
    area: {
      distributed: true
    }
  }
  var colors = ['#85B3AF', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', "#f9a3a4", "#f48024"];
  var bar_options = {
    series: series,
    chart: {
      type: 'bar',
      height: 350,
      width: '100%',
    },
    // title: {
    //   text: 'Top 5 most selling products',
    //   align: 'left'
    // }
    plotOptions: plotOptions,
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
    },
    colors: colors,
    xaxis: {
      categories: test.map(function(item) {
        return item.name
      }),
      labels: {
        rotate: 0,
        maxWidth: 50,
        style: {
          fontSize: '0px',
          fontFamily: 'Helvetica, Arial, sans-serif',
          whiteSpace: 'nowrap',
        },
      }
    },
    yaxis: {
      title: {
        text: 'Quantity'
      }
    },
    fill: {
      opacity: 1,
      colors: colors // set fill colors to use the same colors as bars
    },
    tooltip: {
      y: {
        formatter: function(val) {
          return val + " items"
        }
      }
    }
  };

  var line_options = {
    series: series,
    chart: {
      type: 'line',
      height: 350,
      width: '100%',
    },
    plotOptions: plotOptions,
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 2,
      // colors: ['transparent']
    },
    xaxis: {
      categories: test.map(function(item) {
        return item.name
      }),
      labels: {
        rotate: 0,
        maxWidth: 50,
        style: {
          fontSize: '10px',
          fontFamily: 'Helvetica, Arial, sans-serif',
          whiteSpace: 'nowrap',
        },
      }
    },
    yaxis: {
      title: {
        text: 'Quantity'
      }
    },
    fill: {
      opacity: 1,
      colors: colors // set fill colors to use the same colors as bars
    },
    tooltip: {
      y: {
        formatter: function(val) {
          return val + " items"
        }
      }
    }
  };
  var area_options = {
    series: series,
    chart: {
      type: 'area',
      height: 350,
      width: '100%',
      stacked: true,
      dropShadow: {
        enabled: true,
        color: '#000',
        top: 18,
        left: 7,
        blur: 10,
        opacity: 0.2
      }
    },
    plotOptions: plotOptions,
    dataLabels: {
      enabled: false
    },
    stroke: {
      show: true,
      width: 4,
      curve: 'smooth',
      lineCap: 'butt',
      colors: ["#52938D"],
    },
    legend: {
      show: true,
      // showForSingleSeries: true
    },
    xaxis: {
      categories: test.map(function(item) {
        return item.name
      }),
      labels: {
        rotate: 0,
        maxWidth: 50,
        style: {
          fontSize: '10px',
          fontFamily: 'Helvetica, Arial, sans-serif',
          whiteSpace: 'nowrap',
        },
      }
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.9,
      },
      colors: ["#52938D"]
    },
    markers: {
      size: 5,
      colors: ["#52938D"],
      strokeColor: "#A9C9C7",
      strokeWidth: 3
    },
    yaxis: {
      title: {
        text: 'Quantity'
      }
    },
    tooltip: {
      y: {
        formatter: function(val) {
          return val + " items"
        }
      }
    }
  };
  let chart = new ApexCharts(document.getElementById("chart"), bar_options);
  chart.render();

  function ChangeChart(chartType) {
    console.log(chartType.value);
    chart.destroy();
    if (chartType.value == 'line') {
      chart = new ApexCharts(document.getElementById("chart"), line_options);
      chart.render();

    }
    if (chartType.value == 'bar') {
      chart = new ApexCharts(document.getElementById("chart"), bar_options);
      chart.render();

    }
    if (chartType.value == 'area') {
      chart = new ApexCharts(document.getElementById("chart"), area_options);
      chart.render();

    }
  }
  var successfullOrders = <?php echo json_encode(Order::findAll(["status" => "5"])); ?>;
  // console.log(successfullOrders);
  document.getElementById("filter-type").addEventListener("change", function() {
    var selectedOption = this.value;
    FilterByTime(selectedOption);
  });

  function FilterByTime(selectedOption) {
    var currentDate = new Date();

    var timeZoneOffset = 7 * 60;
    var vietnamTimeOffset = currentDate.getTimezoneOffset() + timeZoneOffset;
    var currentDateVietnam = new Date(currentDate.getTime() + vietnamTimeOffset * 60000);
    var totalRevenue = document.getElementById("total-revenue")
    var pending = document.getElementById("total-pending")
    var newUser = document.getElementById("total-newUser")
    var newOrder = document.getElementById("total-newOrder")
    if (selectedOption === 'date') {
      var filteredData = successfullOrders.filter(function(order) {
        var orderDate = new Date(order.attributes.create_at);
        console.log(orderDate.getDate() + "--" + currentDate.getDate())
        return (
          orderDate.getDate() === currentDate.getDate() &&
          orderDate.getMonth() === currentDate.getMonth() &&
          orderDate.getFullYear() === currentDate.getFullYear()
        );
      });
      console.log(filteredData);
      var totalRevenueValue = calculateTotalRevenue(filteredData);
      var pendingValue = calculatePending(filteredData);
      var newUserValue = calculateNewUser(filteredData);
      var newOrderValue = calculateNewOrder(filteredData);

      totalRevenue.textContent = totalRevenueValue + " $";
      pending.textContent = pendingValue;
      newUser.textContent = newUserValue;
      newOrder.textContent = newOrderValue;

    }

    if (selectedOption === 'month') {
      var filteredData = successfullOrders.filter(function(order) {
        var orderDate = new Date(order.attributes.create_at);
        return (
          orderDate.getMonth() === currentDate.getMonth() &&
          orderDate.getFullYear() === currentDate.getFullYear()
        );
      });
      console.log(filteredData);
    }

    if (selectedOption === 'year') {
      var filteredData = successfullOrders.filter(function(order) {
        var orderDate = new Date(order.attributes.create_at);
        return orderDate.getFullYear() === currentDate.getFullYear();
      });
      console.log(filteredData);
    }
  }

  function calculateTotalRevenue(data) {
    var total = 0;
    data.forEach(function(order) {
      total += parseFloat(order.attributes.total_price);
    });
    return total.toFixed(2);
  }

  function calculatePending(data) {
    var count = 0;
    data.forEach(function(order) {
      if (order.status === 0) {
        count++;
      }
    });
    return count;
  }

  function calculateNewUser(data) {
    var uniqueUsers = new Set();
    data.forEach(function(order) {
      uniqueUsers.add(order.email);
    });
    return uniqueUsers.size;
  }

  function calculateNewOrder(data) {
    return data.length;
  }
</script>