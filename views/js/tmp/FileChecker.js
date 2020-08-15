class FileChecker {

   static extensions = ['.pdf', '.sig', '.docx', '.xlsx'];

   static checkSize(file_size) {
      return file_size / 1024 / 1024 < 80;
   }

   static checkExtension(file_name, extension) {
      let is_valid = false;

      if (!extension) {

         for (let ext of FileChecker.extensions) {
            if (file_name.endsWith(ext)) {
               is_valid = true;
               break;
            }
         }

      } else {
         is_valid = file_name.endsWith(extension);
      }

      return is_valid;
   }

   static IsReadyToUpload(files) {
      for (let file of files) {
         if (!FileChecker.checkExtension(file.name) || !FileChecker.checkSize(file.size)) {
            console.log(FileChecker.checkExtension(file.name));
            console.log(FileChecker.checkSize(file.size));
            return false;
         }
      }

      return true;
   }

   static isInternalSign(file) {
      return FileChecker.checkExtension(file.name, '.sig') && file.size / 1024 > 20
   }


}