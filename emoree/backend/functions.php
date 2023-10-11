<?php

// Database connection file 
include_once 'db_connect.php';

// Function to get school information by school ID
function getSchoolInfo($schoolID)

    {
        global $conn;

        $query = 
            "SELECT sp.id AS school_id,
                COUNT(DISTINCT sc.id) AS num_classes,
                COUNT(DISTINCT st.user_id) AS num_teachers,
                COUNT(DISTINCT spu.user_id) AS num_pupils,
                SUM(TIMESTAMPDIFF(DAY, tl.start_date, au.last_login)) AS accumulated_usage_time,
                MAX(au.last_login) AS last_activity_date
            FROM
                school__partners AS sp
            LEFT JOIN 
                school__classes AS sc ON sp.id = sc.school_id
            LEFT JOIN 
                school__teachers AS st ON sc.id = st.class_id
            LEFT JOIN 
                school__pupils AS spu ON sc.id = spu.class_id
            LEFT JOIN 
                auth__users AS au ON spu.user_id = au.id
            LEFT JOIN (
                        SELECT tl.user_id, MIN(tl.timestamp) AS start_date
                        FROM 
                            training__leaderboard AS tl
                        GROUP BY 
                            tl.user_id
                    ) AS tl ON au.id = tl.user_id
                WHERE
                    sp.id = ?
                    AND sc.school_year IN ('21-22', '22-23', '23-24')
                GROUP BY 
                    sp.id";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $schoolID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch school data
        $schoolData = $result->fetch_assoc();

        return $schoolData;
    }

function getClassInfo($classID) 

    {
        global $conn;

        $query = 
        "SELECT sc.id AS class_id,
        sc.section AS class_name,
        sc.creation_date AS creation_date,
        MIN(tl.start_date) AS start_date,
        MAX(au.last_login) AS last_activity_date,
        GROUP_CONCAT(DISTINCT st.user_id) AS teachers_ids,
        SUM(TIMESTAMPDIFF(DAY, tl.start_date, au.last_login)) AS accumulated_usage_time
        FROM
            school__classes AS sc
        LEFT JOIN
            school__teachers AS st ON sc.id = st.class_id
        LEFT JOIN
            school__pupils AS spu ON sc.id = spu.class_id
        LEFT JOIN
            auth__users AS au ON spu.user_id = au.id
        LEFT JOIN
            (SELECT tl.user_id, MIN(tl.timestamp) AS start_date 
                FROM 
                    training__leaderboard AS tl 
                GROUP BY 
                    tl.user_id) AS tl ON au.id = tl.user_id
        WHERE
            sc.id = ?
            AND sc.school_year IN ('21-22', '22-23', '23-24')
        GROUP BY
            class_id, class_name, creation_date";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $classID);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch class data
        $classData = $result->fetch_assoc();

        return $classData;

    }

function getPupilInfo($pupilID) 

    {
        global $conn;
        
        $query = 
        "SELECT au.id AS pupil_id,
            MIN(tl.timestamp) AS start_date,
            MAX(au.last_login) AS last_login_date,
            COUNT(DISTINCT tl.id) AS accumulated_exercises,
            TIMESTAMPDIFF(DAY, MIN(tl.timestamp), MAX(au.last_login)) AS usage_time
        FROM
            auth__users AS au
        LEFT JOIN
            training__leaderboard AS tl ON au.id = tl.user_id
        WHERE 
            au.id = ?
        GROUP BY 
            au.id
        ";
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $pupilID);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pupilData = $result->fetch_assoc();

        return $pupilData;
    }


?>
