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
  margin: 3em 1em;
}
.pdf-header {
  position: fixed;
  background-color: #0059b3;
  padding: 0.5em;
  top: 0;
  left: 0;
  width: 100%;
  color: white;
  min-height: 150px;
  text-align: center;
}

.pdf-header__logo {
  max-width: 150px;
  float: left;
  display: inline-block;
}

.pdf-footer {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #0059b3;
  color: white;
  text-align: center;
}
