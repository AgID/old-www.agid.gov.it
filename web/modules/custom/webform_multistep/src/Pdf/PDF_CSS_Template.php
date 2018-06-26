<?php
  header("Content-type: text/css; charset: UTF-8");
?>
html {
  margin: 0;
}

@page {
  margin: 120px 80px 80px 80px;
}

footer, header {
  position: fixed;
  left: 0;
  margin-left: -2em;
  margin-right: -2em;
  width: 100%;
}

header { top: -120px; }
footer { bottom: -80px; }

header img { width: 100%; }

p.pagebreak { page-break-after: always; }

table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
  max-width: 100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}

ul li h2 { margin: 0; }
