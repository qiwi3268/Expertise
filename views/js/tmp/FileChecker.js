class FileChecker {

   files;

   static extensions = ['.pdf', '.sig', '.docx', '.xlsx'];

   constructor(files) {
      this.files = Array.from(files);


   }


   static isInternalSign(file) {
      return FileChecker.checkExtension(file.name, '.sig')
   }

   checkNames() {
      for (let file of this.files) {
         if (!this.checkExtension(file.name)) {
            return false;
         }

      }

      return true;
   }

   static checkSize(file_size) {
      return file_size / 1024 / 1024 < 80
   }

   static checkExtension(file_name, extension) {
      let is_valid = true;

      if (!extension) {

         for (let ext of FileChecker.extensions) {
            if (file_name.endsWith(ext)) {
               break;
            }

            is_valid = false;
         }

      } else {
         is_valid = file_name.endsWith(extension);
      }

      return is_valid;
   }



   readyToUpload() {
      for (let file of this.files) {
         if (!FileChecker.checkExtension(file.name) || !FileChecker.checkSize(file.size)) {
            return false;
         }
      }

      return true;
   }






}