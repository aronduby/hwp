import subscribeModalInit from "./schedule/subscribe-modal";
import todayRowInit from "./schedule/today-row";
import filterableInit from "./schedule/filterable";

// clicking subscribe opens the modal content
subscribeModalInit();

// adds to today row for reference
todayRowInit();

// handles filtering the table
filterableInit();