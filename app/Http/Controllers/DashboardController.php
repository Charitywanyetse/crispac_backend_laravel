import 'package:flutter/material.dart';
import '../services/dashboard_service.dart';

class DashboardScreen extends StatefulWidget {
  @override
  _DashboardScreenState createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final DashboardService _dashboardService = DashboardService();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Dashboard')),
      body: FutureBuilder<Map<String, dynamic>>(
        future: _dashboardService.getDashboardData(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return Center(child: CircularProgressIndicator());
          }
          if (snapshot.hasError) {
            return Center(child: Text('Error: ${snapshot.error}'));
          }
          final data = snapshot.data!;
          final user = data['user'];
          final stats = data['stats'];
          final recentOrders = data['recent_orders'];

          return ListView(
            padding: EdgeInsets.all(16),
            children: [
              Card(
                child: ListTile(
                  leading: CircleAvatar(child: Text(user['name'][0])),
                  title: Text(user['name']),
                  subtitle: Text(user['email']),
                ),
              ),
              SizedBox(height: 16),
              Text('Stats', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              Row(
                children: [
                  Expanded(child: _statCard('Deliveries', stats['deliveries'].toString())),
                  Expanded(child: _statCard('Rating', stats['rating'].toString())),
                  Expanded(child: _statCard('On Time', '${stats['on_time']}%')),
                ],
              ),
              SizedBox(height: 16),
              Text('Recent Orders', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
              ...recentOrders.map<Widget>((order) => ListTile(
                title: Text('Order #${order['id']}'),
                subtitle: Text('Total: UGX ${order['total']}'),
                trailing: Text(order['status']),
              )).toList(),
            ],
          );
        },
      ),
    );
  }

  Widget _statCard(String label, String value) {
    return Card(
      child: Padding(
        padding: EdgeInsets.all(12),
        child: Column(
          children: [
            Text(value, style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
            Text(label),
          ],
        ),
      ),
    );
  }
}