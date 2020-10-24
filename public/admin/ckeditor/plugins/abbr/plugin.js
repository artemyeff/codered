CKEDITOR.plugins.add('abbr', {
  icons: 'abbr',
  init: function (editor) {
    editor.addCommand('insertTimestamp', {
      exec: function (editor) {
        var now = new Date()
        var e = new Event('testEvent')
        e.id = editor.id
        window.dispatchEvent(e)
        // editor.insertHtml('<img alt="" src="https://www.php.net/images/logo.php" style="height:96px; width:192px" />')
      }
    })
    window.addEventListener('passData' + editor.id, (e) => {
      editor.insertHtml(e.someData)
      console.log(e.someData)
    })
    editor.ui.addButton('Timestamp', {
      label: 'Insert Timestamp',
      command: 'insertTimestamp',
      toolbar: 'insert'
    })
  }
})
