: <?php
2: include'include.php';
3: doDB();
4:
5: //check for required info from the query string
6: if (!isset($_GET['topic_id'])) {
7: header("Location: topiclist.php");
8: exit;
9: }
10:
11: //create safe values for use
12: $safe_topic_id = mysqli_real_escape_string($mysqli, $_GET['topic_id']);
13:
14: //verify the topic exists
15: $verify_topic_sql = "SELECT topic_title FROM forum_topics
16: WHERE topic_id ='".$safe_topic_id."'";
17: $verify_topic_res = mysqli_query($mysqli, $verify_topic_sql)
18: or die(mysqli_error($mysqli));
: if (mysqli_num_rows($verify_topic_res) < 1) {
21: //this topic does not exist
22: $display_block = "<p><em>You have selected an invalid topic.<br/>
23: Please <a href=\"topiclist.php\">try again</a>.</em></p>";
24: } else {
25: //get the topic title
26: while ($topic_info = mysqli_fetch_array($verify_topic_res)) {
27: $topic_title = stripslashes($topic_info['topic_title']);
28: }
29:
30: //gather the posts
31: $get_posts_sql = "SELECT post_id, post_text,
DATE_FORMAT(post_create_time,
32:'%b %e %Y<br/>%r') AS fmt_post_create_time, post_owner
33: FROM forum_posts
34: WHERE topic_id ='".$safe_topic_id."'
35: ORDER BY post_create_time ASC";
36: $get_posts_res = mysqli_query($mysqli, $get_posts_sql)
37: or die(mysqli_error($mysqli));
38:
39: //create the display string
40: $display_block = <<<END_OF_TEXT
41: <p>Showing posts for the <strong>$topic_title</strong> topic:</p>
<table>
43: <tr>
44: <th>AUTHOR</th>
45: <th>POST</th>
46: </tr>
47: END_OF_TEXT;
48:
49: while ($posts_info = mysqli_fetch_array($get_posts_res)) {
50: $post_id = $posts_info['post_id'];
51: $post_text = nl2br(stripslashes($posts_info['post_text']));
52: $post_create_time = $posts_info['fmt_post_create_time'];
53: $post_owner = stripslashes($posts_info['post_owner']);
54:
55: //add to display
56: $display_block .= <<<END_OF_TEXT
57: <tr>
58: <td>$post_owner<br/><br/>
59: created on:<br/>$post_create_time</td>
60: <td>$post_text<br/><br/>
61: <a href="replytopost.php?post_id=$post_id">
: <strong>REPLY TO POST</strong></a></td>
63: </tr>
64: END_OF_TEXT;
65: }
66:
67: //free results
68: mysqli_free_result($get_posts_res);
69: mysqli_free_result($verify_topic_res);
70:
71: //close connection to MySQL
72: mysqli_close($mysqli);
73:
74: //close up the table
75: $display_block .= "</table>";
77: ?>
78: <!DOCTYPE html>
79: <html>
80: <head>
81: <title>Posts in Topic</title>
82: <style type="text/css">
83: table {
84: border: 1px solid black;
85: border-collapse: collapse;
86: }
87: th {
88: border: 1px solid black;
89: padding: 6px;
90: font-weight: bold;
91: background: #ccc;
92: }
93: td {
94: border: 1px solid black;
95: padding: 6px;
96: vertical-align: top;
97: }
98: .num_posts_col { text-align: center; }
99: </style>
100: </head>
101: <body>
102: <h1>Posts in Topic</h1>
103: <?php echo $display_block; ?>
104: </body>
105: </html>