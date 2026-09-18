# Smart Detector – Product Overview

Smart Detector is a real-time threat detection dashboard for web applications. It receives and displays anomaly events from IDS (Intrusion Detection System) agents, stores them for historical querying, and provides an admin interface for managing the full system.

## Core Features

- Real-time event feed from IDS agents via WebSockets
- Event storage, filtering, pagination, and detailed inspection
- Risk classification and threat type management
- User management with role-based access (Admin / User)
- IDS agent and LLM configuration
- System settings administration
- Multi-language support (pt_BR, en, es, fr)

## Domain Concepts

- **Event**: A threat or anomaly detected by an IDS agent, categorized by classification, type, and severity (intrusion/normal)
- **Classification**: Risk level attached to an event
- **Type**: Threat category (e.g., DDoS, SQL Injection)
- **Event Attribute**: Configurable metadata fields attached to events
- **IDS Agent**: External system that sends events to this platform
- **Profile**: User role (Admin or regular User)

## Users

- **Admin**: Full access — user management, system settings, classifications, types, IDS config
- **User**: Read access — view events and dashboards

## Language

The application is primarily written in Portuguese (pt_BR). Route names, UI text, and some variable names may be in Portuguese.
