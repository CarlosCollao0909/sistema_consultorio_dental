<?php 
echo $patient->name . ' ' . $patient->last_name;
echo $patient->phone;
echo calculateAge($patient->birth_date) . ' años';
echo $patient->medical_notes ?: 'N/A';
echo $patient->allergies ?: 'N/A';
?>