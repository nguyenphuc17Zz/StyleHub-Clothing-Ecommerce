import Select from "react-select";

const customStyles = {
  control: (provided) => ({
    ...provided,
    backgroundColor: "#0f3460",
    color: "white",
    borderRadius: "5px",
    border: "none",
    boxShadow: "none",
    width: "220px",
    height: "40px",
  }),
  option: (provided, state) => ({
    ...provided,
    backgroundColor: state.isSelected ? "#0f3460" : "white",
    color: state.isSelected ? "white" : "#0f3460",
    "&:hover": {
      backgroundColor: "#0f3460",
      color: "white",
    },
  }),
  singleValue: (provided) => ({
    ...provided,
    color: "white",
  }),
};

const FilterSelect = ({ categories, onFilterChange }) => {
  const options = [
    { value: "all", label: "All" },
    ...categories.map((cat) => ({
      value: cat.id,
      label: cat.name,
    })),
  ];

  const handleChange = (selectedOption) => {
    onFilterChange(selectedOption);
  };

  return (
    <Select
      options={options}
      placeholder="Filter By Category"
      styles={customStyles}
      onChange={handleChange}
    />
  );
};

export default FilterSelect;
