import { useState, useEffect } from "react";
import "./searchbar.css";
import { productApi } from "../../api/productApi";

const SearchBar = ({ onSearchChange, onSelectSuggestion }) => {
  const [searchWord, setSearchWord] = useState("");
  const [suggestions, setSuggestions] = useState([]);

  const handleChange = (e) => {
    const value = e.target.value;
    setSearchWord(value);
    onSearchChange(value);
  };

  useEffect(() => {
    const timeout = setTimeout(async () => {
      const keyword = searchWord.trim();
      if (!keyword) {
        setSuggestions([]);
        return;
      }
      const data = await productApi.productSearchSuggestion(keyword);
      if (data.success) {
        setSuggestions(data.products); // fix: không bao quanh bằng []
      }
    }, 500);

    return () => clearTimeout(timeout);
  }, [searchWord]);

  const handleSelect = (product) => {
    setSearchWord(product.name); 
    setSuggestions([]);
    if (onSelectSuggestion) onSelectSuggestion(product);
  };

  const highlightText = (text) => {
    const index = text.toLowerCase().indexOf(searchWord.toLowerCase());
    if (index === -1) return text;
    return (
      <>
        {text.substring(0, index)}
        <span className="highlight">
          {text.substring(index, index + searchWord.length)}
        </span>
        {text.substring(index + searchWord.length)}
      </>
    );
  };

  return (
    <div className="search-container">
      <input
        type="text"
        placeholder="Search..."
        value={searchWord}
        onChange={handleChange}
      />
      <ion-icon name="search-outline" className="search-icon"></ion-icon>

      {suggestions.length > 0 && (
        <ul className="suggestion-list">
          {suggestions.map((product) => (
            <li key={product.id} onClick={() => handleSelect(product)}>
              {highlightText(product.name)}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
};

export default SearchBar;
