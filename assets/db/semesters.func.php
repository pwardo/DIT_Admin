<?php
function semester_data($semester_id){
    $semester_id = (int)$semester_id;
    // Security: this will only allow integers
        
    $args = func_get_args();
    // func_get_args() returns an array of user data from databse.
    
    unset ($args[0]);
    // This un sets the first element from the array??? 
    
    $fields = '`'.implode('`, `', $args).'`';
    // impode glues stings together, in this case strings are taken from database.
    // result here is `name``email.
    
    $query = mysql_query("SELECT $fields FROM semesters WHERE semester_id=$semester_id ");
    $query_result = mysql_fetch_assoc($query);
    // retrieve the fields passed from implode function for the current logged in user.
    // and make associative array from the results.
    
    foreach ($args as $field){
        $args[$field ] = $query_result[$field];
        // get data from array for each of the $field names i.e. args[`email`]
        
    }
    return $args;   
}

function semester_check($semester_id){
    $semester_id = (int)$semester_id;
    $query = mysql_query("SELECT COUNT(semester_id) FROM semesters 
        WHERE semester_id=$semester_id
            ");
    
    return (mysql_result($query, 0) == 1) ? TRUE : FALSE;
}

function get_semesters(){
    $semesters = array();
    
    $semesters_query = mysql_query("
        SELECT id, start_date, end_date, value
        FROM semesters
            ORDER BY semesters.id DESC
        ");
     
    while ($semesters_row = mysql_fetch_assoc($semesters_query)){
        // create a multi dimensional array
        // first dimension is semesters, 2nd dimension is images 
        $semesters[] = array(
            'id' => $semesters_row['id'],
            'start_date' => $semesters_row['start_date'],
            'end_date' => $semesters_row['end_date'],
            'value' => $semesters_row['value']
        );
    }
    return $semesters;
}

function create_semester($submitStartDate, $submitEndDate){
    $submitStartDate = mysql_real_escape_string($submitStartDate);
    $submitEndDate = mysql_real_escape_string($submitEndDate);
    
    mysql_query("INSERT INTO Semesters
            VALUES ('','$submitStartDate', '$submitEndDate', '1')
              ") or die(mysql_error());
    
    return mysql_insert_id();
}

function get_semester_data($semester_id){
    $data_query = mysql_query("
        SELECT id, start_date, end_date, value
        FROM Semesters

        WHERE Semesters.id = $semester_id
        ") or die();
        
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $data = array(
            'id' => $data_row['id'],
            'start_date' => $data_row['start_date'],
            'end_date' => $data_row['end_date'],
            'value' => $data_row['value']
        );
    }
    return $data;
}


// --------------------------------------------------------------

function get_courses(){
    $semesters = array();
    
    $semesters_query = mysql_query("
        SELECT id, code, title
        FROM Courses
            ORDER BY id ASC
        ");
     
    while ($semesters_row = mysql_fetch_assoc($semesters_query)){
        // create a multi dimensional array
        // first dimension is semesters, 2nd dimension is images 
        $semesters[] = array(
            'id' => $semesters_row['id'],
            'code' => $semesters_row['code'],
            'title' => $semesters_row['title'],
        );
    }
    return $semesters;
}

function get_course_data($course_id){
    $data_query = mysql_query("
        SELECT id, code, title
        FROM Courses

        WHERE Courses.id = $course_id
        ") or die();
        
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $data = array(
            'id' => $data_row['id'],
            'code' => $data_row['code'],
            'title' => $data_row['title']
        );
    }
    return $data;
}

function get_semester_courses($course_id, $semester_id){
    $semester_id = (int)$semester_id;
    $course_id = (int)$course_id;
    
    $query = mysql_query("
            SELECT COUNT(Courses_id)
            FROM Semesters_has_Courses
            WHERE Courses_id=$course_id
            AND Semesters_id=$semester_id
            ") or die(mysql_error());
    return (mysql_result($query, 0) == 1) ? TRUE : FALSE;
}

// --------------------------------------------------------------
function get_my_modules($registraion_id){
    $modules = array();
    
    $modules_query = mysql_query("
        SELECT Registrations_id, Modules_id, grade
        FROM registrations_has_modules
        WHERE Registrations_id='$registraion_id'
        ");
     
    while ($modules_row = mysql_fetch_assoc($modules_query)){
        // create a multi dimensional array
        // first dimension is modules, 2nd dimension is images 
        $modules[] = array(
            'Registrations_id' => $modules_row['Registrations_id'],
            'Modules_id' => $modules_row['Modules_id'],
            'grade' => $modules_row['grade'],
        )or die("display_db_query:" . mysql_error());
    }
    return $modules;
}

function get_course_modules($course_id){
    $modules = array();
    
    $modules_query = mysql_query("
        SELECT id, code, title
        FROM Modules
        WHERE Courses_id='$course_id'
        ");
     
    while ($modules_row = mysql_fetch_assoc($modules_query)){
        // create a multi dimensional array
        // first dimension is modules, 2nd dimension is images 
        $modules[] = array(
            'id' => $modules_row['id'],
            'code' => $modules_row['code'],
            'title' => $modules_row['title'],
        )or die("display_db_query:" . mysql_error());
    }
    return $modules;
}

function get_module_lecturer($module_id){  
    $data_query = mysql_query("
        SELECT Staff_staff_id
        FROM modules_has_staff
        WHERE modules_id='$module_id'
        ") or die("display_db_query:" . mysql_error());
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $data = array(
            'Staff_staff_id' => $data_row['Staff_staff_id']
        ) or die("display_db_query:" . mysql_error());
    }
    return $data;
}

function get_my_assigned_modules($staff_id){
    $modules = array();
    
    $modules_query = mysql_query("
        SELECT Modules_id
        FROM modules_has_staff
        WHERE Staff_staff_id='$staff_id'
        ")or die("display_db_query:" . mysql_error());
     
    while ($modules_row = mysql_fetch_assoc($modules_query)){
        // create a multi dimensional array
        // first dimension is modules, 2nd dimension is images 
        $modules[] = array(
            'Modules_id' => $modules_row['Modules_id']
        )or die("display_db_query:" . mysql_error());
    }
    return $modules;
}


function get_module_data($module_id){
    $data_query = mysql_query("
        SELECT id, code, title, open_closed, Courses_id
        FROM Modules

        WHERE Modules.id = $module_id
        ") or die();
        
    
    while ($data_row = mysql_fetch_assoc($data_query)){
        $data = array(
            'id' => $data_row['id'],
            'code' => $data_row['code'],
            'title' => $data_row['title'],
            'open_closed' => $data_row['open_closed'],
            'Courses_id' => $data_row['Courses_id']
        );
    }
    return $data;
}

// -------------------------------------------------------------------------------

function get_lecturers(){
    $lecturers = array();
    
    $lecturers_query = mysql_query("
        SELECT staff_id, first_name, last_name
        FROM staff
        WHERE Job_type_id = '2'
            ORDER BY staff_id
        ");
     
    while ($lecturers_row = mysql_fetch_assoc($lecturers_query)){
        // create a multi dimensional array
        // first dimension is lecturers, 2nd dimension is images 
        $lecturers[] = array(
            'staff_id' => $lecturers_row['staff_id'],
            'first_name' => $lecturers_row['first_name'],
            'last_name' => $lecturers_row['last_name'],
        );
    }
    return $lecturers;
}

function create_course($submitCourseCode, $submitCourseTitle){
    $submitCourseCode = mysql_real_escape_string($submitCourseCode);
    $submitCourseTitle = mysql_real_escape_string($submitCourseTitle);
    
    mysql_query("INSERT INTO Courses
            VALUES ('','$submitCourseCode', '$submitCourseTitle')
              ") or die(mysql_error());
    
    return mysql_insert_id();
}

function create_module($submitModuleCode, $submitModuleTitle, $course_id){
    $submitModuleCode = mysql_real_escape_string($submitModuleCode);
    $submitCourseTitle = mysql_real_escape_string($submitCourseTitle);
    $course_id = mysql_real_escape_string($course_id);

    mysql_query("INSERT INTO Modules
            VALUES ('','$submitModuleCode', '$submitModuleTitle','1', '$course_id')
              ") or die(mysql_error());
    
    return mysql_insert_id();
}

// -------------------------------------------------------------------------------

function get_module_clases($modules_id){
    $classes = array();
    
    $classes_query = mysql_query("
        SELECT class_id, Days_id, Rooms_id, Time_Slots_id, Modules_id
        FROM Classes
        WHERE Modules_id='$modules_id'
        ");
     
    while ($classes_row = mysql_fetch_assoc($classes_query)){
        // create a multi dimensional array
        // first dimension is classes, 2nd dimension is images 
        $classes[] = array(
            'class_id' => $classes_row['class_id'],
            'Days_id' => $classes_row['Days_id'],
            'Rooms_id' => $classes_row['Rooms_id'],
            'Time_Slots_id' => $classes_row['Time_Slots_id'],
            'Modules_id' => $classes_row['Modules_id']            
        )or die("display_db_query:" . mysql_error());
    }
    return $classes;
}


function get_timeslots(){
    $timeslots = array();
    
    $timeslots_query = mysql_query("
        SELECT id, startTime, endTime
        FROM Time_Slots
        ");
     
    while ($timeslots_row = mysql_fetch_assoc($timeslots_query)){
        // create a multi dimensional array
        // first dimension is timeslots, 2nd dimension is images 
        $timeslots[] = array(
            'id' => $timeslots_row['id'],
            'startTime' => $timeslots_row['startTime'],
            'endTime' => $timeslots_row['endTime']
        );
    }
    return $timeslots;
}

function get_days(){
    $days = array();
    
    $days_query = mysql_query("
        SELECT id, day
        FROM Days
        ORDER BY id
        ");
     
    while ($days_row = mysql_fetch_assoc($days_query)){
        // create a multi dimensional array
        // first dimension is days, 2nd dimension is images 
        $days[] = array(
            'id' => $days_row['id'],
            'day' => $days_row['day']
        );
    }
    return $days;
}

function get_rooms(){
    $rooms = array();
    
    $rooms_query = mysql_query("
        SELECT id, name, capacity
        FROM rooms
        ");
     
    while ($rooms_row = mysql_fetch_assoc($rooms_query)){
        // create a multi dimensional array
        // first dimension is rooms, 2nd dimension is images 
        $rooms[] = array(
            'id' => $rooms_row['id'],
            'name' => $rooms_row['name'],
            'capacity' => $rooms_row['capacity']
        );
    }
    return $rooms;
}


// --------------------------------------------------------

function get_class_data($class_id){
    $class_data_query = mysql_query("
        SELECT DISTINCT 
            classes.class_id AS id, 
            Time_Slots.id AS Time_slot_id, Time_Slots.startTime AS startTime, Time_Slots.endTime AS endTime,
            Rooms.id AS room_id, Rooms.name AS roomName, Rooms.capacity AS roomCapacity, 
            Days.id AS day_id, Days.Day AS day, 
            modules.code AS moduleCode, 
            modules.title AS moduleTitle, modules.id AS module_id
        FROM classes INNER JOIN Time_Slots INNER JOIN Days INNER JOIN Rooms INNER JOIN modules
        WHERE classes.Time_slots_id = time_slots.id 
            AND classes.days_id = days.id 
            AND classes.rooms_id = rooms.id 
            AND classes.modules_id = modules.id 
            AND Classes.class_id = $class_id

        ")or die("display_db_query:" . mysql_error());
    
    while ($class_data_row = mysql_fetch_assoc($class_data_query)){
        $class_data = array(

            'id' => $class_data_row['id'],
            'Time_slot_id' => $class_data_row['Time_slot_id'],
            'startTime' => $class_data_row['startTime'],
            'endTime' => $class_data_row['endTime'],
            'room_id' => $class_data_row['room_id'],
            'roomName' => $class_data_row['roomName'],
            'roomCapacity' => $class_data_row['roomCapacity'],
            'day_id' => $class_data_row['day_id'],
            'day' => $class_data_row['day'],
            'module_id' => $class_data_row['module_id'],
            'moduleCode' => $class_data_row['moduleCode'],
            'moduleTitle' => $class_data_row['moduleTitle']
            
            )or die("display_db_query:" . mysql_error());
    }
    return $class_data;
}