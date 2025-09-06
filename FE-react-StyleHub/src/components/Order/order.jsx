import { Fragment, useEffect, useState } from "react";
import { Container, Row, Col, Table, Button, Modal } from "react-bootstrap";
import { URL_IMAGE } from "../../api/apiUrl";
import { orderApi } from "../../api/orderApi";
import "./order.css";

const OrderList = () => {
  const [orders, setOrders] = useState([]);
  const [selectedOrder, setSelectedOrder] = useState(null);
  const [showModal, setShowModal] = useState(false);

  const fetchOrders = async () => {
    try {
      const res = await orderApi.getAllOrders();
      if (res.success) {
        setOrders(res.orders ?? []);
      }
    } catch (err) {
      console.error("Lỗi lấy orders:", err);
    }
  };

  useEffect(() => {
    fetchOrders();
  }, []);
  const fetchItem = async (id) => {
    try {
      const res = await orderApi.getItems(id);
      if (res.success) {
        return res.items;
      }
    } catch (err) {
      console.error("Lỗi lấy items:", err);
    }
  };
  const handleShowDetails = async (id) => {
    try {
      const res = await orderApi.getItems(id);
      if (res.success) {
        console.log(res.items);
        setSelectedOrder(res.items);
        setShowModal(true);
      }
    } catch (err) {
      console.error("Lỗi load chi tiết order:", err);
    }
  };

  return (
    <section className="orders-section">
      <Fragment>
        <Container>
          <h2 className="mb-4">📦 My Orders</h2>

          {orders.length === 0 && <h5>No orders yet.</h5>}

          {orders.length > 0 && (
            <Table bordered hover responsive className="order-table">
              <thead className="table-dark">
                <tr>
                  <th>ID</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                {orders.map((o) => (
                  <tr key={o.id}>
                    <td>#{o.id}</td>
                    <td>{new Date(o.created_at).toLocaleDateString()}</td>
                    <td>
                      <span className={`status-badge ${o.status}`}>
                        {o.status}
                      </span>
                    </td>
                    <td>${Number(o.total_amount).toFixed(2)}</td>
                    <td>
                      <Button
                        variant="info"
                        size="sm"
                        onClick={() => handleShowDetails(o.id)}
                      >
                        View Details
                      </Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </Table>
          )}

          {/* Modal hiển thị chi tiết order */}
          <Modal
            show={showModal}
            onHide={() => setShowModal(false)}
            size="lg"
            centered
          >
            <Modal.Header closeButton>
              <Modal.Title>Order Details #{selectedOrder?.id}</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              {selectedOrder && (
                <>
                  <Row className="mb-3">
                    <Col>
                      <strong>Status:</strong>{" "}
                      <span className={`status-badge ${selectedOrder.status}`}>
                        {selectedOrder.status}
                      </span>
                    </Col>
                    <Col>
                      <strong>Total:</strong> $
                      {Number(selectedOrder.total_amount).toFixed(2)}
                    </Col>
                  </Row>

                  <Table bordered hover responsive className="detail-table">
                    <thead className="table-light">
                      <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                      </tr>
                    </thead>
                    <tbody>
                      {selectedOrder.items?.map((item) => (
                        <tr key={item.id}>
                          <td className="d-flex align-items-center">
                            <img
                              src={`${URL_IMAGE}/uploads/products/${item.product.thumbnail}`}
                              alt={item.product.name}
                              className="thumb me-2"
                            />
                            {item.product.name}
                          </td>
                          <td>
                            {item.variant.size} - {item.variant.color}
                          </td>
                          <td>{item.quantity}</td>
                          <td>${Number(item.price).toFixed(2)}</td>
                          <td>
                            $
                            {(
                              Number(item.price) * Number(item.quantity)
                            ).toFixed(2)}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </Table>
                </>
              )}
            </Modal.Body>
          </Modal>
        </Container>
      </Fragment>
    </section>
  );
};

export default OrderList;
