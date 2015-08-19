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

    class RestaurantTest extends PHPUnit_Framework_TestCase {

        function testUpdate()
        {
            //Arrange
            $name = "Steve's";
            $id = null;
            $address = "123";
            $cuisine_id = 1;
            $test_restaurant = new Restaurant($name, $address, $id, $cuisine_id);
            $test_restaurant->save();

            $new_name = "Mike's new and better place";

            //Act
            $test_restaurant->update($new_name);

            //Assert
            $this->assertEquals("Mike's new and better place", $test_restaurant->getName());
        }

        function test_Delete() {
            $name = "Rest 1";
            $id = null;
            $address = "123";
            $cuisine_id = 1;
            $test_restaurant = new Restaurant($name, $address, $id, $cuisine_id);
            $test_restaurant->save();

            $name2 = "Rest 1";
            $id2 = null;
            $address2 = "123";
            $cuisine_id2 = 1;
            $test_restaurant2 = new Restaurant($name2, $address2, $id2, $cuisine_id2);
            $test_restaurant2->save();

            $test_restaurant->delete();

            $this->assertEquals([$test_restaurant2], Restaurant::getAll());
        }

        function test_findByName() {
            $name = "Rest 1";
            $address = "Add 1";
            $id = null;
            $cuisine_id = 1;
            $name2 = "Rest 2";
            $address2 = "Add 2";
            $test_restaurant = new Restaurant($name, $address, $id, $cuisine_id);
            $test_restaurant->save();
            $test_restaurant2 = new Restaurant($name2, $address2, $id, $cuisine_id);
            $test_restaurant2->save();
        }

    }
?>
