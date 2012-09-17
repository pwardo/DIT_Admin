<?php
function logged_in(){
    return isset($_SESSION['user_id']);
}

function login_check($username, $password){
    $username = mysql_real_escape_string($username) or die(mysql_error()); // sanitise string
    
    $login_query = mysql_query("SELECT COUNT(`user_id`) as `count`, `user_id` FROM `users` WHERE `username`='$username' AND `password`='$password'")  or die(mysql_error());  
//    count the user id, condition is where email and password match a current user. count will be 0 or 1. 1 if user is registered.
    
    return(mysql_result($login_query, 0) == 1) ? mysql_result($login_query, 0, 'user_id') : false;
//    if result is 1, then there is a match and we want to return the user_id. ELSE return FALSE.
}

function user_data($user_id){
    $args = func_get_args();
    // func_get_args() returns an array of user data from database.
    
    $fields = '`'.implode('`, `', $args).'`';
    // impode glues stings together, in this case strings are taken from database.
    // result here is `name``email.
    
    $query = mysql_query("SELECT $fields FROM users WHERE user_id =".$_SESSION['user_id']) or die(mysql_error());
    $query_result = mysql_fetch_assoc($query);
    // retrieve the fields passed from implode function for the current logged in user.
    // and make associative array from the results.
    
    foreach ($args as $field){
        $args[$field] = $query_result[$field];
        // get data from array for each of the $field names i.e. args[`email`]
        
    }
    return $args;
}

function student_data($user_id){
    $args = func_get_args();
    // func_get_args() returns an array of user data from database.
    
    $fields = '`'.implode('`, `', $args).'`';
    // impode glues stings together, in this case strings are taken from database.
    // result here is `name``email.
    
    $query = mysql_query("SELECT $fields FROM Students WHERE Students.SystemLogin_user_id =".$_SESSION['user_id']) or die(mysql_error());
    $query_result = mysql_fetch_assoc($query);
    // retrieve the fields passed from implode function for the current logged in user.
    // and make associative array from the results.
    
    foreach ($args as $field){
        $args[$field] = $query_result[$field];
        // get data from array for each of the $field names i.e. args[`email`]
        
    }
    return $args;
}

function staff_data($user_id){
    $args = func_get_args();
    // func_get_args() returns an array of user data from database.
    
    $fields = '`'.implode('`, `', $args).'`';
    // impode glues stings together, in this case strings are taken from database.
    // result here is `name``email.
    
    $query = mysql_query("SELECT $fields FROM staff WHERE staff.SystemLogin_user_id =".$_SESSION['user_id']) or die(mysql_error());
    $query_result = mysql_fetch_assoc($query);
    // retrieve the fields passed from implode function for the current logged in user.
    // and make associative array from the results.
    
    foreach ($args as $field){
        $args[$field] = $query_result[$field];
        // get data from array for each of the $field names i.e. args[`email`]
        
    }
    return $args;
}

function registration_data($student_id){
    $args = func_get_args();
    // func_get_args() returns an array of user data from database.
    
    $fields = '`'.implode('`, `', $args).'`';
    // impode glues stings together, in this case strings are taken from database.
    // result here is `name``email.
    
    $query = mysql_query("SELECT $fields FROM Registrations WHERE Registrations.Students_student_id =".$student_id) or die(mysql_error());
    $query_result = mysql_fetch_assoc($query);
    // retrieve the fields passed from implode function for the current logged in user.
    // and make associative array from the results.
    
    foreach ($args as $field){
        $args[$field] = $query_result[$field];
        // get data from array for each of the $field names i.e. args[`email`]
        
    }
    return $args;
}

function student_register($studentID, $firstName, $lastName, $email, $password, $courseID, $semesterID, $mobile, $addressL1, $addressL2, $addressL3, $addressL4){
    $studentID = mysql_real_escape_string($studentID);
    $firstName = mysql_real_escape_string($firstName); // sanitise name
    $lastName = mysql_real_escape_string($lastName); // sanitise name
    $email = mysql_real_escape_string($email);
    $password = mysql_real_escape_string($password);
    $courseID = mysql_real_escape_string($courseID); 
    $semesterID = mysql_real_escape_string($semesterID);
    
    $mobileNumber = mysql_real_escape_string($mobile);
    $addressL1 = mysql_real_escape_string($addressL1); 
    $addressL2 = mysql_real_escape_string($addressL2); 
    $addressL3 = mysql_real_escape_string($addressL3); 
    $addressL4 = mysql_real_escape_string($addressL4); 
    
    
    mysql_query("
            INSERT INTO Users 
            VALUES('','$studentID', '$password')
        ") or die(mysql_error());
    
    
    $user_id = mysql_insert_id();
    
    mysql_query("
            INSERT INTO Students (student_id, first_name, last_name, address_line1_street, address_line2_town, address_line3_county, address_line4, email, phone_mobile, SystemLogin_user_id) 
            VALUES('$studentID', '$firstName', '$lastName', '$addressL1', '$addressL2', '$addressL3', '$addressL4', '$email', '$mobileNumber', '$user_id')
        ") or die(mysql_error());
    
    mysql_query("
            INSERT INTO Users_has_Roles (Users_user_id, roles_role_id)
            VALUES ('$user_id', 3)
        ") or die(mysql_error());;
        
    mysql_query("
            INSERT INTO Registrations (Students_student_id, Courses_id, Semesters_id)
            VALUES ('$studentID', '$courseID', '$semesterID')
        ") or die(mysql_error());;
    
}

function staff_register($staffID, $firstName, $lastName, $email, $password, $mobile, $addressL1, $addressL2, $addressL3, $addressL4){
    $staffID = mysql_real_escape_string($staffID);
    $firstName = mysql_real_escape_string($firstName); // sanitise name
    $lastName = mysql_real_escape_string($lastName); // sanitise name
    $email = mysql_real_escape_string($email);
    $password = mysql_real_escape_string($password);
    
    $mobileNumber = mysql_real_escape_string($mobile);
    $addressL1 = mysql_real_escape_string($addressL1); 
    $addressL2 = mysql_real_escape_string($addressL2); 
    $addressL3 = mysql_real_escape_string($addressL3); 
    $addressL4 = mysql_real_escape_string($addressL4); 
    
    
    mysql_query("
            INSERT INTO Users (username, password)
            VALUES('$staffID', '$password')
        ") or die(mysql_error());
    
    $user_id = mysql_insert_id();

    mysql_query("
            INSERT INTO staff (staff_id, first_name, last_name, address_line1_street, address_line2_town, address_line3_county, address_line4, email, phone_mobile, Job_type_id, SystemLogin_user_id) 
            VALUES('$staffID', '$firstName', '$lastName', '$addressL1', '$addressL2', '$addressL3', '$addressL4', '$email', '$mobileNumber', '2', '$user_id')
        ") or die(mysql_error());
    
    mysql_query("
            INSERT INTO Users_has_Roles (Users_user_id, roles_role_id)
            VALUES ('$user_id', 2)
        ");
          
}



function student_exists($studentID){
    $studentID = mysql_real_escape_string($studentID); // sanitise email
   
    $query = mysql_query("SELECT COUNT(student_id) FROM students WHERE student_id = '$studentID'"); // returns integer 
    return(mysql_result($query, 0) == 1) ? true : false; 
    // if returned integer is 0, user is not registered. If interger is 1, student ID is already registered.
}

function staff_exists($staffID){
    $staffID = mysql_real_escape_string($staffID); // sanitise email
   
    $query = mysql_query("SELECT COUNT(staff_id) FROM staff WHERE staff_id = '$staffID'"); // returns integer 
    return(mysql_result($query, 0) == 1) ? true : false; 
    // if returned integer is 0, user is not registered. If interger is 1, student ID is already registered.
}


function course_semester_check($courseID, $semesterID){
    $courseID = (int)$courseID; 
    $semesterID = (int)($semesterID);
   
    $query = mysql_query("SELECT COUNT(Semesters_id) FROM semesters_has_courses WHERE Semesters_id = '$semesterID'
            AND Courses_id = '$courseID'
            "); // returns integer 
    return(mysql_result($query, 0) == 1) ? false : true; 
    // if returned integer is 0, user is not registered. If interger is 1, student ID is already registered.
}

function get_registration_data($student_id){
    $data_query = mysql_query("
        SELECT id, Courses_id, date, Semesters_id
        FROM Registrations

        WHERE Students_student_id = $student_id
        ") or die();
        
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $reg_data = array(
            'id' => $data_row['id'],
            'Courses_id' => $data_row['Courses_id'],
            'date' => $data_row['date'],
            'Semesters_id' => $data_row['Semesters_id']
        );
    }
    return $reg_data;
}

function check_lecturer_module_count($staff_id){
    $data_query = mysql_query("
        SELECT Staff_staff_id, 
        COUNT(*) AS module_count 
        FROM modules_has_staff 
        WHERE modules_has_staff.Staff_staff_id = '$staff_id';
        ") or die();
        
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $reg_data = array(
            'module_count' => $data_row['module_count'],
            'Staff_staff_id' => $data_row['Staff_staff_id']
        );
    }
    return $reg_data;
}

function check_enrolment_count($reg_id){
    $data_query = mysql_query("
        SELECT Registrations_id, 
        COUNT(*) AS enrolled_count 
        FROM Registrations_has_Modules 
        WHERE Registrations_has_Modules.Registrations_id = '$reg_id'
        ") or die();
        
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $reg_data = array(
            'enrolled_count' => $data_row['enrolled_count'],
            'Registrations_id' => $data_row['Registrations_id']
        );
    }
    return $reg_data;
}

function enrolled($reg_id, $module_id){
    $reg_id = (int)$reg_id;
    $module_id = (int)$module_id;
    
    $query = mysql_query("SELECT COUNT(Registrations_id) FROM registrations_has_modules 
            WHERE Registrations_id = '$reg_id'
            AND Modules_id = '$module_id'
            "); // returns integer 
    return(mysql_result($query, 0) == 1) ? true : false; 
    // if returned integer is 0, user is not registered. If interger is 1, student ID is already registered.
}

function get_module_enrolments($modules_id){
    $classes = array();
    
    $classes_query = mysql_query("
        SELECT Registrations_id, Modules_id
        FROM Registrations_has_Modules
        WHERE Modules_id='$modules_id'
        ");
     
    while ($classes_row = mysql_fetch_assoc($classes_query)){
        // create a multi dimensional array
        // first dimension is classes, 2nd dimension is images 
        $classes[] = array(
            'Registrations_id' => $classes_row['Registrations_id'],
            'Modules_id' => $classes_row['Modules_id'],
        )or die("display_db_query:" . mysql_error());
    }
    return $classes;
}



function get_enrolment_data($module_id){
    $classes = array();
    $classes_query = mysql_query("
        SELECT 
            registrations_has_modules.Registrations_id AS Registrations_id, 
            registrations_has_modules.grade AS grade, 
            registrations_has_modules.Modules_id AS Modules_id, 
            registrations.Students_student_id AS Student_id,
            Students.first_name AS first_name,
            Students.last_name AS last_name    

        FROM registrations_has_modules INNER JOIN registrations INNER JOIN Students
        WHERE registrations_has_modules.Registrations_id = registrations.id 
            AND registrations.Students_student_id = Students.student_id 
            AND registrations_has_modules.Modules_id = '$module_id'

        ")or die("display_db_query:" . mysql_error());
    
    while ($classes_row = mysql_fetch_assoc($classes_query)){
        $classes[] = array(

            'Registrations_id' => $classes_row['Registrations_id'],
            'grade' => $classes_row['grade'],
            'Modules_id' => $classes_row['Modules_id'],
            'Student_id' => $classes_row['Student_id'],
            'first_name' => $classes_row['first_name'],
            'last_name' => $classes_row['last_name']
            
            )or die("display_db_query:" . mysql_error());
    }
    return $classes;
}

function get_student_grades($registration_id){
    $classes = array();
    $classes_query = mysql_query("
        SELECT
            registrations_has_modules.Registrations_id AS Registrations_id, 
            registrations_has_modules.grade AS grade, 
            registrations_has_modules.Modules_id AS Modules_id, 
            Modules.code AS Modules_code,
            Modules.title AS Modules_title

        FROM registrations_has_modules 
            INNER JOIN Modules
        WHERE Registrations_id = '$registration_id'
            AND Modules.id = registrations_has_modules.Modules_id

        ")or die("display_db_query:" . mysql_error());
    
    while ($classes_row = mysql_fetch_assoc($classes_query)){
        $classes[] = array(

            'Registrations_id' => $classes_row['Registrations_id'],
            'grade' => $classes_row['grade'],
            'Modules_id' => $classes_row['Modules_id'],
            'Modules_code' => $classes_row['Modules_code'],
            'Modules_title' => $classes_row['Modules_title']
            
            )or die("display_db_query:" . mysql_error());
    }
    return $classes;
}

function get_student_data($student_id){
    $user_data_query = mysql_query("
        SELECT student_id, first_name, last_name, address_line1_street, address_line2_town, address_line3_county, address_line4, email, phone_mobile, phone_home, SystemLogin_user_id
        FROM Students

        WHERE Students.student_id = $student_id
        ") or die();
        
    
    while ($user_data_row = mysql_fetch_assoc($user_data_query)){
        $user_data = array(
            'student_id' => $user_data_row['student_id'],
            'first_name' => $user_data_row['first_name'],
            'last_name' => $user_data_row['last_name'],
            'address_line1_street' => $user_data_row['address_line1_street'],
            'address_line2_town' => $user_data_row['address_line2_town'],
            'address_line3_county' => $user_data_row['address_line3_county'],
            'address_line4' => $user_data_row['address_line4'],
            'email' => $user_data_row['email'],
            'phone_mobile' => $user_data_row['phone_mobile'],
            'phone_home' => $user_data_row['phone_home'],
            'SystemLogin_user_id' => $user_data_row['SystemLogin_user_id']
        );
    }
    return $user_data;
}

function get_student_data_userid($user_id){
    $user_data_query = mysql_query("
        SELECT student_id, first_name, last_name, address_line1_street, address_line2_town, address_line3_county, address_line4, email, phone_mobile, phone_home, SystemLogin_user_id
        FROM Students

        WHERE Students.SystemLogin_user_id = $user_id
        ") or die();
        
    
    while ($user_data_row = mysql_fetch_assoc($user_data_query)){
        $user_data = array(
            'student_id' => $user_data_row['student_id'],
            'first_name' => $user_data_row['first_name'],
            'last_name' => $user_data_row['last_name'],
            'address_line1_street' => $user_data_row['address_line1_street'],
            'address_line2_town' => $user_data_row['address_line2_town'],
            'address_line3_county' => $user_data_row['address_line3_county'],
            'address_line4' => $user_data_row['address_line4'],
            'email' => $user_data_row['email'],
            'phone_mobile' => $user_data_row['phone_mobile'],
            'phone_home' => $user_data_row['phone_home'],
            'SystemLogin_user_id' => $user_data_row['SystemLogin_user_id']
        );
    }
    return $user_data;
}

function get_staff_data($staff_id){
    $user_data_query = mysql_query("
        SELECT staff_id, first_name, last_name, address_line1_street, address_line2_town, address_line3_county, address_line4, email, phone_mobile, phone_home, SystemLogin_user_id
        FROM staff

        WHERE staff.staff_id = $staff_id
        ") or die();
        
    
    while ($user_data_row = mysql_fetch_assoc($user_data_query)){
        $user_data = array(
            'staff_id' => $user_data_row['staff_id'],
            'first_name' => $user_data_row['first_name'],
            'last_name' => $user_data_row['last_name'],
            'address_line1_street' => $user_data_row['address_line1_street'],
            'address_line2_town' => $user_data_row['address_line2_town'],
            'address_line3_county' => $user_data_row['address_line3_county'],
            'address_line4' => $user_data_row['address_line4'],
            'email' => $user_data_row['email'],
            'phone_mobile' => $user_data_row['phone_mobile'],
            'phone_home' => $user_data_row['phone_home'],
            'Job_type_id' => $user_data_row['Job_type_id'],
            'SystemLogin_user_id' => $user_data_row['SystemLogin_user_id']
        );
    }
    return $user_data;
}

function get_staff_data_by_user_id($user_id){
    $user_data_query = mysql_query("
        SELECT staff_id, first_name, last_name, address_line1_street, address_line2_town, address_line3_county, address_line4, email, phone_mobile, phone_home
        FROM staff

        WHERE staff.SystemLogin_user_id = '$user_id'
        ") or die();
        
    
    while ($user_data_row = mysql_fetch_assoc($user_data_query)){
        $user_data = array(
            'staff_id' => $user_data_row['staff_id'],
            'first_name' => $user_data_row['first_name'],
            'last_name' => $user_data_row['last_name'],
            'address_line1_street' => $user_data_row['address_line1_street'],
            'address_line2_town' => $user_data_row['address_line2_town'],
            'address_line3_county' => $user_data_row['address_line3_county'],
            'address_line4' => $user_data_row['address_line4'],
            'email' => $user_data_row['email'],
            'phone_mobile' => $user_data_row['phone_mobile'],
            'phone_home' => $user_data_row['phone_home'],
            'Job_type_id' => $user_data_row['Job_type_id'],
            'SystemLogin_user_id' => $user_data_row['SystemLogin_user_id']
        );
    }
    return $user_data;
}

function class_scheduled($timeslot, $day, $room){
    $timeslot = (int)$timeslot;
    $day = (int)$day;
    $room = (int)$room;
       
    $query = mysql_query("SELECT COUNT(class_id) FROM Classes 
            WHERE time_slots_id = '$timeslot'
            AND Days_id = '$day'
            AND Rooms_id = '$room'
            ")  or die("display_db_query:" . mysql_error()); // returns integer 
    return(mysql_result($query, 0) == 1) ? true : false; 
    // if returned integer is 0, user is not registered. If interger is 1, student ID is already registered.
}
?>