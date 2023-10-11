// Select the input field, search button, and search type dropdown
const schoolIdInput = document.getElementById("schoolIdInput");
const searchButton = document.getElementById("searchButton");
const searchTypeDropdown = document.getElementById("searchType");
const placeholderContent = document.querySelector(".placeholder-content");

// Add a click event listener to the search button
searchButton.addEventListener("click", function () {
    // Get the school ID entered by the user
    const schoolID = schoolIdInput.value;

    // Get the selected search type
    const searchType = searchTypeDropdown.value;

     // Define the PHP file based on the search type
     let phpFile;
     let idParamName; // Variable to store the ID parameter name
     if (searchType === "school") {
         phpFile = "getSchoolData.php";
         idParamName = "schoolID"; // Use "schoolID" for school
     } else if (searchType === "class") {
         phpFile = "getClassData.php";
         idParamName = "classID"; // Use "classID" for class
     } else if (searchType === "pupil") {
         phpFile = "getPupilData.php";
         idParamName = "pupilID"; // Use "pupilID" for pupil
     }

// Perform an AJAX request to fetch data based on the school ID and search type
fetch(`../backend/${phpFile}?${idParamName}=${schoolID}&searchType=${searchType}`)
    .then(response => response.json())
    .then(data => {
        // Check if the data contains valid information
        switch (searchType) {
            case "school":
                placeholderContent.innerHTML = `
                    <h2>School Information</h2>
                    <p>School ID: ${data.school_id}</p>
                    <p>Number of Classes: ${data.num_classes}</p>
                    <p>Number of Teachers: ${data.num_teachers}</p>
                    <p>Number of Pupils: ${data.num_pupils}</p>
                    <p>Accumulated Usage Time: ${formatUsageTime(data.accumulated_usage_time)}</p>
                    <p>Last Activity Date: ${data.last_activity_date}</p>
                `;   break;
                case "class":
                placeholderContent.innerHTML = `
                    <h2>Class Information</h2>
                    <p>Class ID: ${data.class_id}</p>
                    <p>Class Name: ${data.class_name}</p>
                    <p>Start date: ${data.start_date}</p>
                    <p>Creation date: ${data.creation_date}</p>
                    <p>Teachers: ${formatTeachers(data.teachers_ids)}</p>
                    <p>Accumulated Usage Time: ${formatUsageTime(data.accumulated_usage_time)}</p>
                    <p>Last Activity Date: ${data.last_activity_date}</p>
                `;                
                
                break;
                case "pupil":
                    placeholderContent.innerHTML = `
                    <h2>Pupil Information</h2>
                    <p>Pupil's ID: ${data.pupil_id}</p>
                    <p>Start date: ${data.start_date}</p>
                    <p>Last Login: ${data.last_login_date}</p>
                    <p>Accumulated number of exercises: ${data.accumulated_exercises}</p>
                    <p>Usage time: ${formatUsageTime(data.usage_time)}</p>
                `;                
                    break;
             default:
                // Handle the case where the school data is not found
                placeholderContent.innerHTML = '<p>No data found for this ID.</p>';
            }
        })

        
        .catch(error => {
            console.error("Error:", error);
            // Handle the error here, e.g., display an error message to the user
            placeholderContent.innerHTML = '<p>An error occurred while fetching data.</p>';
        });

        function formatUsageTime(usageTimeInDays) {
            const years = Math.floor(usageTimeInDays / 365);
            const months = Math.floor((usageTimeInDays % 365) / 30);
            const days = Math.floor(usageTimeInDays % 365) % 30;
            
            const result = [];
        
            if (years > 0) {
                result.push(`${years} year${years > 1 ? 's' : ''}`);
            }
        
            if (months > 0) {
                result.push(`${months} month${months > 1 ? 's' : ''}`);
            }
        
            if (days > 0) {
                result.push(`${days} day${days > 1 ? 's' : ''}`);
            }
        
            return result.join(' ');
        }
        


function formatTeachers(teachers) {
    if (teachers === null) {
        return "No teacher assigned";
    } else {
        return teachers;
    }
}

        
});
