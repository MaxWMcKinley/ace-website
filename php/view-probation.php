<?php

// --------------------------------------------------------------------------------------------
// Connect to database
// --------------------------------------------------------------------------------------------

// Set up connection variables
$hostname="localhost";
$username="acesan7_max";
$password="dbpw2669";
$dbname="acesan7_db";

// Connecting to database
$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_errno)
	echo "Failed to connect to database with error number " . $conn->connect_errno . " (" . $conn->connect_error . ")";


// --------------------------------------------------------------------------------------------
// Get eventids of the probation event
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT id FROM event_archive WHERE name = 'Probation'")))
	echo "Select probation id preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->execute())
	echo "Select probation id execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($id))
	echo "Select probation id result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

while ($stmt->fetch()) {
	$probationid = $id;
}


// --------------------------------------------------------------------------------------------
// Get attendance of each member
// --------------------------------------------------------------------------------------------

if (!($stmt = $conn->prepare("SELECT members.first_name, members.last_name FROM members, points WHERE members.uin = points.uin AND points.eventid = ? ORDER BY members.first_name")))
	echo "Select probation preparation failed with error number " . $conn->errno . " (" . $conn->error . ")";

if (!$stmt->bind_param("s", $probationid))
		echo "Select probation parameter binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->execute())
	echo "Select probation execute failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

if (!$stmt->bind_result($first, $last))
	echo "Select probation result binding failed with error number " . $stmt->errno . " (" . $stmt->error . ")";

$i = 0;
while ($stmt->fetch()) {
	$i++;
	echo $i . ": " . $first . " " . $last . "<br><br>";
}

$voters = 80;
echo "<br><br>Number needed to elect Treasurer: " . floor((($voters - 2) / 2) + 1);
echo "<br>Number needed to elect Secretary: " . floor((($voters - 3) / 2) + 1);
echo "<br>Number needed to elect Member-at-Large: " . floor((($voters - 4) / 2) + 1);

$quorum = ceil(2/3 * (87 - $i));
echo "<br><br>Eligible people to vote: " . (87 - $i);
echo "<br>Quorum is " . $quorum . "<br>";
echo "If ammendment passes, quroum is: " . 87 * 2/3 . "<br><br>";

for ( $j = $quorum; $j <= (87 - $i); $j++ ) {
	echo " Att: " . $j . " Pass#: ". ceil(($j * 2/3)) . "<br><br>";
}

$stmt->close();
$conn->close();
?>
