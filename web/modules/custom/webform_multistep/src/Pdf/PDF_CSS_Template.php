<?php
  header("Content-type: text/css; charset: UTF-8");
?>
html, body {
  margin: 0;
}
* {
margin: 0;
box-sizing: border-box;
}

table {
  max-width: 100%;
}
.pdf-content {
  margin: 100px 1em 50px 1em;
}
.pdf-header {
  position: fixed;
  background-color: #0059b3;
  padding: 0.5em;
  top: 0;
  left: 0;
  width: 100%;
  color: white;
  height: 50px;
  text-align: center;
}

.pdf-header__logo {
  max-width: 150px;
  float: left;
  display: inline-block;
}

.pdf-footer {
  position: fixed;
  bottom: 0px;
  left: 0;
  width: 100%;
  height: 75px;
  background-color: #0059b3;
  text-align: center;
  color: white;
}

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
