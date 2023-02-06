import {
  mdiPointOfSale,
  mdiSelectGroup,
} from "@mdi/js";

export default [
  {
    href: "/users",
    label: "Users",
    icon: mdiSelectGroup,
    roles: ["Admin"]
  },
  {
    href: "/purchases",
    label: "Purchases",
    icon: mdiPointOfSale,
    roles: ["Admin","Customer"]
  },
];
