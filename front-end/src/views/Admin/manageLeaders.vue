<template>
  <v-data-table
    :headers="headers"
    :items="leaders"
    sort-by="first_name"
    show-expand
    :single-expand="true"
    :expanded.sync="expanded"
    item-key="first_name"
    class="elevation-1"
  >
    <template v-slot:[`item.image`]="{ item }">
      <img :src="item.image" alt="لا يوجد صورة" />
    </template>
    <template v-slot:expanded-item="{ item }">
      <td>
        <v-card>
          العمر:
          {{
            Math.floor((new Date() - new Date(item.birth_date)) / 31557600000)
          }}
        </v-card>
      </td>
      <td>
        <v-card> ر.ب.و: {{ item.cin }} </v-card>
      </td>
      <td>
        <v-card> البريد الالكتروني: {{ item.email }} </v-card>
      </td>
      <td>
        <v-card> رقم الهاتف: {{ item.phone_number }} </v-card>
      </td>
      <td>
        <v-card>
          تاريخ انشاء الحساب:
          {{
            new Date(item.created_at).getDate() +
            "/" +
            (new Date(item.created_at).getMonth() + 1) +
            "/" +
            new Date(item.created_at).getFullYear()
          }}
        </v-card>
      </td>
    </template>
    <template v-slot:[`item.actions`]="{ item }">
      <div class="align-center">
      <v-btn  class="mx-1" :disabled="item.roles[0].status==0" fab x-small color="red">
        <v-icon color="white">mdi-close-outline</v-icon>
      </v-btn>
       <v-btn class="mx-1" :disabled="item.roles[0].status==1" fab x-small color="success">
        <v-icon>mdi-check-outline</v-icon>
      </v-btn>
      </div>
    </template>
  </v-data-table>
</template>
<script>
export default {
  created() {
    this.getLeaders();
  },
  data() {
    return {
      expanded: [],
      singleExpand: false,
      leaders: [],
    };
  },
  methods: {
    getLeaders() {
      this.$axios
        .get("/leaders")
        .then((response) => {
          this.leaders = response.data;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    edit(item) {
      console.log(item);
    },
  },
  computed: {
    headers() {
      return [
        {
          text: "الاسم",
          value: "first_name",
        },
        {
          text: "اللقب",
          value: "last_name",
        },
        {
          text: "المهمة",
          value: "roles[0].role.name",
        },
        {
          text: "الفرقة",
          value: "roles[0].troupe.name",
          default: "الفرقة",
        },
        {
          text: "الصورة",
          value: "image",
          sortable: false,
          align: "center",
          class: "img-responsive",
        },
        {
          text: "اجرائات",
          value: "actions",
          sortable: false,
        },
        { text: "", value: "data-table-expand" },
      ];
    },
  },
};
</script>

<style>
</style>