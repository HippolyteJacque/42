using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class collideBullet : MonoBehaviour {

	public GameObject shooter;
	public GameObject gm;

	// Use this for initialization
	void OnCollisionEnter2D(Collision2D coll) {
		if (coll != null && shooter != null && shooter.tag == "enemy" && coll.gameObject.tag == "Player"){
			Destroy(gameObject);
			gm.GetComponent<GameManager>().winOrLose = 1;
			Destroy(coll.gameObject);
		}
		else if (coll != null && shooter != null && shooter.tag == "Player" && coll.gameObject.tag == "enemy"){
			Destroy(gameObject);
			gm.GetComponent<GameManager>().enemyCount -= 1;
			Destroy(coll.gameObject);
		}
		else {
			Destroy(gameObject);
		}
	}
}
