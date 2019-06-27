using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Enemy : MonoBehaviour {

	private float moveSpeed = 1.5f;
	private Vector3 target;
	public float chaseRadius;
	public float attackRadius;
	public Transform homePosition;
	public GameObject legs;
	public bool isChasing = false;
	public int patrol = 1;
	private Vector3 coorPatrol;
	private Vector3 coorPatrolStart;
	public int roomID;
	public GameObject player;
	public GameObject door1;
	private Vector3 door;
	private RaycastHit2D hit;

	private Vector2 Door1;
	private Vector2 Door2;
	private Vector2 Door3;
	private Vector2 Door4;
	private Vector2 Door5;

	// Use this for initialization
	void Start () {
		target = new Vector2(player.transform.position.x, player.transform.position.y);
		homePosition = transform;
		coorPatrol = new Vector3(homePosition.position.x + 5, homePosition.position.y, homePosition.position.z);
		coorPatrolStart = new Vector3(homePosition.position.x, homePosition.position.y, homePosition.position.z);

		Door1 = new Vector2(0f, 3.455f);
		Door2 = new Vector2(5.5f, 8f);
		Door3 = new Vector2(10f, 12.5f);
		Door4 = new Vector2(5.5f, 17f);
		Door5 = new Vector2(-5.5f, 17f);
	}
	
	// Update is called once per frame
	void Update () {
		if (player != null){
			target = new Vector2(player.transform.position.x, player.transform.position.y);
		}
	}

	void FixedUpdate(){
		//CheckDistance();
	}

	IEnumerator chaseOn(float time){
	    
		yield return new WaitForSeconds(time);
		isChasing = false;
		legs.GetComponent<Animator>().SetBool("isWalking", false);
		
}

	// void OnDrawGizmosSelected()
    // {
    //     // Draw a yellow sphere at the transform's position
    //     Gizmos.color = Color.yellow;
    //     Gizmos.DrawSphere(gameObject.transform.Find("centerZoneChase").transform.position, chaseRadius);
    // }

	public void CheckDistance(){
		// print("enemyID");
		// print(roomID);
		// print("heroID");
		// print(player.GetComponent<HeroMove>().roomID);
		Vector3 posChaseCenter = gameObject.transform.Find("centerZoneChase").transform.position;
		
		

		RaycastHit2D hitall;
 
        hitall = Physics2D.Raycast (posChaseCenter, player.transform.position - posChaseCenter, chaseRadius);
			
			if (patrol == 1 && isChasing == false && transform.position != coorPatrol) {
				transform.position = Vector3.MoveTowards(transform.position, coorPatrol, (moveSpeed - 0.5f) * Time.deltaTime);

			}
			else if (patrol == 1 && transform.position == coorPatrol) {
				transform.localRotation *= Quaternion.Euler(0, 0, 180);
				patrol = 2;
			}
			else if (patrol == 2 && isChasing == false && transform.position != coorPatrolStart) {
				transform.position = Vector3.MoveTowards(transform.position, coorPatrolStart, (moveSpeed - 0.5f) * Time.deltaTime);
			}
			else if (patrol == 2 && transform.position == coorPatrolStart) {
				transform.localRotation *= Quaternion.Euler(0, 0, 180);
				patrol = 1;
			}
			if (player.GetComponent<HeroMove>().roomID == roomID || (Input.GetKey(KeyCode.Mouse0) && Vector3.Distance(target, posChaseCenter) <= chaseRadius + 2)){
				if (isChasing == true || hitall && hitall.transform.tag == "Player" || (Input.GetKey(KeyCode.Mouse0) && Vector3.Distance(target, posChaseCenter) <= chaseRadius + 2)){
					patrol = 3;
					if (Vector3.Distance(target, transform.position) > attackRadius){
						isChasing = true;
						StartCoroutine(chaseOn(15));

						hit = Physics2D.Raycast(transform.position, target);
						if (hit != false) {
							if(hit.transform.tag == "wall"){
								target = hit.point;
							}
						}
						legs.GetComponent<Animator>().SetBool("isWalking", true);

						transform.position = Vector3.MoveTowards(transform.position, target, moveSpeed * Time.deltaTime);

						target = new Vector3(player.transform.position.x, player.transform.position.y, 0);

						Vector3 dir = target - transform.position;
						float angle = Mathf.Atan2(dir.y,dir.x) * Mathf.Rad2Deg;
						transform.rotation = Quaternion.AngleAxis(angle + 90, Vector3.forward);
					}
					else if (Vector3.Distance(target, transform.position) < attackRadius) {

						Vector3 dir = target - transform.position;
						float angle = Mathf.Atan2(dir.y,dir.x) * Mathf.Rad2Deg;
						transform.rotation = Quaternion.AngleAxis(angle + 90, Vector3.forward);

						if (Time.time > GetComponentInChildren<bulletShot>().fireRate && GetComponentInChildren<bulletShot>().weapon.GetComponent<Weapon>().ammo > 0)
						{
							GetComponentInChildren<bulletShot>().fireRate = Time.time + GetComponentInChildren<bulletShot>().fireSpeed;
							GetComponentInChildren<bulletShot>().Projectile.GetComponent<collideBullet>().shooter = GetComponentInChildren<bulletShot>().Shooter;
							GetComponentInChildren<bulletShot>().cloneProj = (GameObject)Instantiate(GetComponentInChildren<bulletShot>().Projectile, GetComponentInChildren<bulletShot>().firePoint.transform.position, transform.rotation);
							GetComponentInChildren<bulletShot>().cloneProj.transform.Rotate(0, 0, -90);
							if (GetComponentInChildren<bulletShot>().Projectile.name == "bulletSaber") {	
								Destroy(GetComponentInChildren<bulletShot>().cloneProj, 0.1f);
							}
							else if (GetComponentInChildren<bulletShot>().Shooter.tag == "enemy") {
								GetComponentInChildren<bulletShot>().cloneProj.GetComponent<Rigidbody2D>().velocity = -transform.up * GetComponentInChildren<bulletShot>().speed;
							}
							else {
								GetComponentInChildren<bulletShot>().weapon.GetComponent<Weapon>().ammo -= 1;
								GetComponentInChildren<bulletShot>().cloneProj.GetComponent<Rigidbody2D>().velocity = -transform.up * GetComponentInChildren<bulletShot>().speed;
							}	
						}
						legs.GetComponent<Animator>().SetBool("isWalking", false);
					}
				}
				else {
					StopAllCoroutines();
				}
			}
			else if (player.GetComponent<HeroMove>().roomID != roomID && isChasing == true){
				if ((roomID == 0 && player.GetComponent<HeroMove>().roomID == 1) || (roomID == 1 && player.GetComponent<HeroMove>().roomID == 0)) {
				door = Door1;
			}
			else if ((roomID == 1 && player.GetComponent<HeroMove>().roomID == 2) || (roomID == 2 && player.GetComponent<HeroMove>().roomID == 1) || (roomID == 3 && player.GetComponent<HeroMove>().roomID == 1)) {
				door = Door2;
			}
			else if ((roomID == 2 && player.GetComponent<HeroMove>().roomID == 3) || (roomID == 3 && player.GetComponent<HeroMove>().roomID == 2) || (roomID == 4 && player.GetComponent<HeroMove>().roomID == 1)) {
				door = Door3;
			}
			else if ((roomID == 3 && player.GetComponent<HeroMove>().roomID == 4) || (roomID == 4 && player.GetComponent<HeroMove>().roomID == 3) || (roomID == 5 && player.GetComponent<HeroMove>().roomID == 1)) {
				door = Door4;
			}
			else if ((roomID == 4 && player.GetComponent<HeroMove>().roomID == 5) || (roomID == 5 && player.GetComponent<HeroMove>().roomID == 4)) {
				door = Door5;
			}
			hit = Physics2D.Raycast(transform.position, door);
			if (hit != false) {
				if(hit.transform.tag == "wall"){
					door = hit.point;
				}
			}
			legs.GetComponent<Animator>().SetBool("isWalking", true);

			transform.position = Vector3.MoveTowards(transform.position, door, moveSpeed * Time.deltaTime);

			door = new Vector3(door.x, door.y, 0);

			Vector3 dir = door - transform.position;
			float angle = Mathf.Atan2(dir.y,dir.x) * Mathf.Rad2Deg;
			transform.rotation = Quaternion.AngleAxis(angle + 90, Vector3.forward);
			}
	}

	void OnTriggerEnter2D(Collider2D coll) {
		if (coll.tag == "room0") {
			roomID = 0;
		}
		else if (coll.tag == "room1") {
			roomID = 1; 
		}
		else if (coll.tag == "room2") {
			roomID = 2; 
		}
		else if (coll.tag == "room3") {
			roomID = 3; 
		}
		else if (coll.tag == "room4") {
			roomID = 4; 
		}
		else if (coll.tag == "room5") {
			roomID = 5; 
		}
    }


}