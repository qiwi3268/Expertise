/**
 * Вспомогательный класс для отправки запроса проверки открепленной подписи
 */
class ExternalSignatureVerifier {

   /**
    *
    * @param fs_name_data
    * @param fs_name_sign
    * @param mapping_1
    * @param mapping_2
    * @return {Promise<unknown>}
    */
   static execute (fs_name_data, fs_name_sign, mapping_1, mapping_2) {
      let form_data = this.getFormData(fs_name_data, fs_name_sign, mapping_1, mapping_2);
      return API.sendRequest(
         'post',
         '/home/API_external_signature_verifier',
         form_data,
         null,
         'json',
         null,
         null
      );
   }

   static getFormData (fs_name_data, fs_name_sign, mapping_1, mapping_2) {
      let form_data = new FormData();
      form_data.append('fs_name_data', fs_name_data);
      form_data.append('fs_name_sign', fs_name_sign);
      form_data.append('mapping_level_1', mapping_1);
      form_data.append('mapping_level_2', mapping_2);
      return form_data;
   }

}