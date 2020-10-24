CKEDITOR.plugins.add('filemanager', {
  icons: 'filemanager',
  init: function (editor) {
    editor.addCommand('insertImage', {
      exec: function (editor) {
        const e = document.createEvent('CustomEvent')
        e.initCustomEvent('openFileManager', true, true,{
          editor: editor.id
        })

        window.dispatchEvent(e)
      }
    })
    window.addEventListener('insertImageUrl' + editor.id, function (e) {
      editor.insertHtml('<img src="' + e.detail.url + '" width="200"/>')
    })
    editor.ui.addButton('Filemanager', {
      label: 'Выбрать изображение',
      command: 'insertImage',
      toolbar: 'insert',
      icon: this.path + 'icons/filemanager.png'
    })
  }
})
