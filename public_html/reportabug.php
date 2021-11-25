<?php include 'header.php';?>
<link rel="stylesheet" href="/styles/styles.css">

<body>
    <div class="containerReportBug">
        <p id="NewBugReport">Report New Bug</p>

        <form>
            <label>Title:</label>
            <input type="text" name="title">
            <label>Frequency:</label>
			<input type="text" name="frequency">
            <label>Serverity:</label>
            <input type="text" name="serverity">
            <label>Description:</label>
			<textarea></textarea>
			<button id="bugSend" type="submit" name="submit">Send</button>
        </form>
    </div>    
</body>




<?php include 'footer.php';?>