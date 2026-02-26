<template>
  <div>
    <textarea :id="editorId"></textarea>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount, ref } from "vue";

const props = defineProps({
  modelValue: String,
});
const emit = defineEmits(["update:modelValue"]);

const editorId = `tinymce-editor-${Math.floor(Math.random() * 100000)}`;
const editorInstance = ref(null);

function initTinyMCE() {
  window.tinymce.init({
    selector: `#${editorId}`,
    height: 300,
    menubar: true,
    plugins: ["lists", "link", "image", "preview", "visualblocks", "advlist", "lists","charmap","anchor","searchreplace","code","fullscreen","insertdatetime","media","table","help","wordcount","autolink"],
    toolbar: "undo redo | blocks | formatselect | bold italic backcolor| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    skin_url: "/tinymce/js/tinymce/skins/ui/oxide",
    content_css: "/tinymce/js/tinymce/skins/content/default/content.css",
    language: "pt_BR",
    language_url: "/tinymce/js/tinymce/langs/pt_BR.js",

    convert_urls: false,
    automatic_uploads: true,
    file_picker_types: "image",

    images_upload_handler: (blobInfo, progress) => {
      return new Promise((resolve, reject) => {
        const formData = new FormData();
        formData.append("file", blobInfo.blob(), blobInfo.filename());

        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.getAttribute("content") : "";

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/upload");
        xhr.setRequestHeader("X-CSRF-TOKEN", token);

        xhr.upload.onprogress = (e) => {
          if (e.lengthComputable) {
            const percent = (e.loaded / e.total) * 100;
            progress(percent);
          }
        };

        xhr.onload = () => {
          if (xhr.status !== 200) {
            return reject(`Erro no upload: ${xhr.status}`);
          }

          let json;
          try {
            json = JSON.parse(xhr.responseText);
          } catch (e) {
            return reject("Resposta inválida do servidor.");
          }

          if (json.location) {
            console.log("Imagem enviada com sucesso:", json.location);
            resolve(json.location); // Importante: precisa ser a URL absoluta
          } else {
            reject(json.error || "Erro ao fazer upload da imagem.");
          }
        };

        xhr.onerror = () => {
          reject("Erro na requisição de upload.");
        };

        xhr.send(formData);
      });
    },

    file_picker_callback: (callback, value, meta) => {
      if (meta.filetype === "image") {
        window.open("/image-picker", "Image Picker", "width=800,height=600");
        window.SetUrl = function (url) {
          callback(url, { alt: "" });
        };
      }
    },

    setup(editor) {
      editorInstance.value = editor;

      editor.on("init", () => {
        editor.setContent(props.modelValue || "");
      });

      editor.on("change keyup", () => {
        emit("update:modelValue", editor.getContent());
      });
    },
  });
}

onMounted(() => {
  if (!window.tinymce) {
    const script = document.createElement("script");
    script.src = "/tinymce/js/tinymce/tinymce.min.js";
    script.onload = () => initTinyMCE();
    document.head.appendChild(script);
  } else {
    initTinyMCE();
  }
});

onBeforeUnmount(() => {
  if (editorInstance.value) {
    editorInstance.value.destroy();
  }
});
</script>