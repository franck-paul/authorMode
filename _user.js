/*global $, jsToolBar */
'use strict';

$(function() {
  if (typeof jsToolBar === 'function') {
    var tbUser = new jsToolBar(document.getElementById('user_desc'));
    tbUser.draw('xhtml');
  }
});
