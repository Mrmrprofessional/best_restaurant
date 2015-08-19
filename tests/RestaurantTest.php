<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Cuisine.php";
    require_once "src/Restaurant.php";

    $server = 'mysql:host=localhost;dbname=food_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CuisineTest extends PHPUnit_Framework_TestCase {

        function testUpdate()
        {
            //Arrange
            $name = "Steve's";
            $id = null;
            $test_restaurant = new Restaurant($name, $id);
            $test_restaurant->save();

            $new_name = "Mike's new and better place";

            //Act
            $test_restaurant->update($new_name);

            //Assert
            $this->assertEquals("Mike's new and better place", $test_restaurant->getName());
        }
    }
?>
