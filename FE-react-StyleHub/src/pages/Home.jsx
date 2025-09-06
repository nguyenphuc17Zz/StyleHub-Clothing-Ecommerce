import { Fragment } from "react";
import Wrapper from "../components/wrapper/Wrapper";
import Section from "../components/Section";
import { products, discoutProducts } from "../utils/products";
import SliderHome from "../components/Slider";
import useWindowScrollToTop from "../hooks/useWindowScrollToTop";
import { useState, useEffect } from "react";
import { productApi } from "../api/productApi";
const Home = () => {
  const [products_latest, setProducts_Latest] = useState([]);

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    const fetchLatest = async () => {
      try {
        const res = await productApi.getLatest();
        if (res.success) {
          setProducts_Latest(res.products_latest );

        }
        setError(error.message);
      } finally {
        setLoading(false);
      }
    };

    fetchLatest();
  }, []);

  const newArrivalData = products.filter(
    (item) => item.category === "mobile" || item.category === "wireless"
  );
  const bestSales = products.filter((item) => item.category === "sofa");
  useWindowScrollToTop();
  return (
    <Fragment>
      <SliderHome />
      <Wrapper />
      <Section
        title="New Arrivals"
        bgColor="white"
        productItems={products_latest}
      />
    </Fragment>
  );
};

export default Home;
