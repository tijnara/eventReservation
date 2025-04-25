<!-- a webpage section that showcases food packages with details about price per person (pax), 
the food list, package inclusions, and additional charges. -->

<!-- Masthead Section: Displays the title "Packages" at the top of the page -->
<header class="masthead">

	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Packages</title>
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
	
    <!-- Google Fonts link for Playball font -->
    <link href="https://fonts.googleapis.com/css2?family=Playball&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
	
    <!-- Displays the title "Packages" at the top of the page -->	
    
       <div class="row h-100 align-items-center justify-content-center text-center">
             <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                <!-- Page Title ----------------------->
                <h1 style="font-family: 'Great Vibes';font-size: 6rem; color: white;">Our Food Packages</h1>
                <div class="divider"></div>
            </div>
        </div>
    
</header>

<!-- Main Section: Displays food package and the package details/information -->
<section class="page-section" style="position: relative; background-image: url('assets/img/package.jpg'); background-attachment: fixed;">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4">
			
                <!-- Food List Section ------------>
                <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                    <h1 style="font-family: 'Playball'; color: white; font-size: 5rem;">Food List</h1>
                    <div class="custom-divider"></div>
                </div>
            </div>
        </div>

        <!-- Food Package: Displays the price per pax and food list from the database -->
        <div class="card-body">
            <table class="table table-condensed table-hover" style="font-size: 20px; width: 70%; margin: auto;">
                <thead>
					
					<!-- price per pax column------------>
                    <tr>
					<th>Price Per Pax</th>
                        <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Food List</th>
                    </tr>
					
                </thead>
                <tbody>
				
                    <?php 
                    // gets all food menu items from the database
                    $menu = $conn->query("SELECT * FROM menu");
                    while ($row = $menu->fetch_assoc()): 					
					?>
					
                    <tr>
                        <td>
                            <!-- Displays price per pax for the food package -->
                            <p><b>
							
                            <?php 
                                $perPax = explode(',', $row['perPax']);
                                foreach ($perPax as $perPaxItem) {
                                    echo "<br>" . trim($perPaxItem) . "</br>";
                                }
                            ?></b></p>
							
                        </td>
						
                        <td>
                            <!-- Displays the list of foods in the food package -->
                            <p>
                                <ul>
                                    <?php 
                                    $foods = explode(',', $row['foods']);
                                    foreach ($foods as $food) {
                                        echo "<li>" . trim($food) . "</li>";
                                    }
                                    ?>
                                </ul>
                            </p>
							
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Package Inclusions and Additional Charges Section -->
        <div class="col-lg-12">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-10 align-self-end mb-4">
                    <!-- Section title for Package Inclusions and Additional Charges -->
                    <div class="text-center" style="background-color: rgba(0, 0, 0, 0.5); padding: 5px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);">
                        <h1 style="font-family: 'Playball', cursive; color: white; font-size: 4.55rem;">Package Inclusions and Additional Charges</h1>
                        <div class="custom-divider1"></div>
                    </div>
                </div>
            </div>

            <!-- Table for displaying package inclusions and additional charges -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card-body">
                        <table class="table table-condensed table-hover" style="font-size: 20px; width: 70%; margin: auto;">
                            <tbody>
                                <?php 
                                // gets all package data from the database
                                $packages = $conn->query("SELECT * FROM package");
                                $package_data = [];
                                while ($row = $packages->fetch_assoc()):
                                    $package_data[] = $row; 
                                endwhile; ?>

                                <tr>
                                    <!-- Displays package inclusions for each package -->
                                    <td><b>Package Inclusions</b></td>
                                    <?php foreach ($package_data as $package): ?>
                                    <td>
                                        <ul>
                                            <?php 
                                            $addcharges = explode("\n", $package['inclusion']);
                                            foreach ($addcharges as $charge): ?>
                                                <li style="margin-bottom: 10px;"><?php echo ucwords(trim($charge)); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>

                                <tr>
                                    <!-- Displays additional charges for each package -->
                                    <td><b>Additional Charges</b></td>
                                    <?php foreach ($package_data as $package): ?>
                                    <td>
                                        <ul style="line-height: 2;">
                                            <?php 
                                            $addcharges = explode("\n", $package['addcharges']);
                                            foreach ($addcharges as $charge): ?>
                                                <li><?php echo ucwords(trim($charge)); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* for background image of Table to fix the position */
.page-section {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}

/* custom dividers */

/* ourpackages */
.divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db);
    width: 70%; 
    margin: 20px auto; 
    border-radius: 5px; 
}

/* foodlist divider */
.custom-divider {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 14%; 
    margin: 20px auto; 
    border-radius: 5px; 
}

/* inclusionsandcharges styles */
.custom-divider1 {
    border: 0;
    height: 2px;
    background: linear-gradient(to right, #f39c12, #3498db); 
    width: 75%; 
    margin: 20px auto; 
    border-radius: 5px; 
}

/* for Table's transparency */
table.table {
    background-color: rgba(255, 255, 255, .5);
    border-radius: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
}

tbody tr {
    background-color: rgba(255, 255, 255, .5);
    transition: background-color 0.1s ease;
}

thead th {
    background-color: rgba(255, 255, 255, .5);
}

tbody:hover {
    background-color: rgba(255, 255, 255, .85);
}

/* to avoid horizontal scrollbar */
body {
    overflow-x: hidden; 
}

/* Adjust height and ensure no overflow */
.page-section {
    height: auto; 
}

.container {
    max-width: 100%; 
    padding: 0 15px; 
}
</style>
