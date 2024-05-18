<?php
 function formatDate($dateString) {
    // Create a DateTime object from the input date string
    $dateTime = new DateTime($dateString);

    // Format the DateTime object to the desired format
    $formattedDate = $dateTime->format('F j, Y');

    // Return the formatted date
    return $formattedDate;
}

function formatFabIcon($iconString){
  $parts = explode(':', $iconString);

  if (count($parts) !== 2 || $parts[0] !== 'fa-brands') {
    return ''; // Return empty string for invalid format
  }

  return "fab fa-{$parts[1]}";
}

// function includeWithVariables($filePath, $variables = array(), $print = true)
// {
//     // Extract the variables to a local namespace
//     extract($variables);

//     // Start output buffering
//     ob_start();

//     // Include the template file
//     include $filePath;

//     // End buffering and return its contents
//     $output = ob_get_clean();
//     if (!$print) {
//         return $output;
//     }
//     echo $output;
// }