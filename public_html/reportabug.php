<?php include 'header.php';?>
<link rel="stylesheet" href="/styles/styles.css">

<body>
    <div class="containerReportBug">
        <p id="NewBugReport">Report New Bug</p>

        <form id="">
            <label>Title:</label>
            <input type="text" name="title">
            <label>Frequency:</label>
			<input type="text" name="frequency">
            <label>Serverity:</label>
            <input type="text" name="serverity">
            <label>Description:</label>
			<input type="text" name="description">
			<button id="bugSend" type="submit" name="submit">Send</button>
        </form>
    </div>    
</body>




<?php include 'footer.php';?>